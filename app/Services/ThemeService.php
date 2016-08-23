<?php
namespace App\Services;

use App\Data\Blog\Image;
use App\Data\Blog\MonthlyProfessional;
use App\Data\Blog\MonthlyTheme;
use App\Data\Blog\MonthlyThemeLanguage;
use App\Data\Blog\MonthlyThemeSubject;
use App\Data\Blog\Post;

use DB;
use Validator;
use Config;

class ThemeService
{
    public static function create($input)
    {
        $monthlyThemeSubject = MonthlyThemeSubject::create([
            'theme_name' => $input['subject_theme_name'],
            'short_name' => self::convertToShortName($input['subject_theme_name']),
            'img' => self::uploadImg($input['image']),
            'display_slider' => $input['display_slider'],
            'publish_month' => $input['publish_month'],
            'publish_year' => $input['publish_year'],
        ]);

        $urlDetails = self::parseUrls($input['url'], $input['professional_imgs'], $input['slider_imgs']);
        foreach ($urlDetails as $urlDetail) {
            MonthlyProfessional::create([
                'monthly_theme_subject_id' => $monthlyThemeSubject->id,
                'url' => $urlDetail['url'],
                'post_id' => $urlDetail['postId'],
                'order' => $urlDetail['order'],
                'professional_img' => !is_null($urlDetail['professionalImg']) ? self::uploadImg($urlDetail['professionalImg']) : null,
                'slider_img' => !is_null($urlDetail['sliderImg']) ? self::uploadImg($urlDetail['sliderImg']) : null,
            ]);
        }

        foreach ($input['theme_name']['vi'] as $order => $themeNameVi) {
            $monthlyTheme = MonthlyTheme::create([
                'short_name' => self::convertToShortName($input['theme_name']['en'][$order]),
                'monthly_theme_subject_id' => $monthlyThemeSubject->id,
                'order' => $order + 1,
            ]);
            MonthlyThemeLanguage::create([
                'monthly_theme_id' => $monthlyTheme->id,
                'name' => $input['theme_name']['vi'][$order],
                'language_code' => 'vi',
            ]);
            MonthlyThemeLanguage::create([
                'monthly_theme_id' => $monthlyTheme->id,
                'name' => $input['theme_name']['en'][$order],
                'language_code' => 'en',
            ]);
            MonthlyThemeLanguage::create([
                'monthly_theme_id' => $monthlyTheme->id,
                'name' => $input['theme_name']['ja'][$order],
                'language_code' => 'ja',
            ]);
        }
        return true;
    }

    public static function parseUrls($urls, $professionalImgs, $sliderImgs)
    {
        $result = [];
        foreach ($urls as $order => $url) {
            $urlArr = explode('/', $url);
            $encryptedPostId = end($urlArr);
            $post = Post::findByEncryptedId($encryptedPostId);
            if ($post) {
                $result[$order] = [
                    'url' => $url,
                    'postId' => $post->id,
                    'order' => $order,
                    'professionalImg' => $professionalImgs[$order],
                    'sliderImg' => $sliderImgs[$order],
                ];
            }
        }
        return $result;
    }

    public static function update($input)
    {
        $monthlyThemeSubjectId = (int) $input['monthlyThemeSubjectId'];
        $monthlyThemeSubject = MonthlyThemeSubject::find($monthlyThemeSubjectId);
        if ($monthlyThemeSubject) {
            $checkAllUpdate = true;
            DB::beginTransaction();
            $updateMonthlyThemeSubject = $monthlyThemeSubject->update([
                                            'theme_name' => $input['subject_theme_name'],
                                            'short_name' => self::convertToShortName($input['subject_theme_name']),
                                            'img' => !empty($input['image']) ? self::uploadImg($input['image']) : ($input['userChangeMainPicture'] ? null : $monthlyThemeSubject->img),
                                            'display_slider' => $input['display_slider'],
                                        ]);
            if ($updateMonthlyThemeSubject) {
                DB::commit();
            } else {
                DB::rollback();
                $checkAllUpdate = false;
            }

            DB::beginTransaction();
            $urlDetails = isset($input['url']) ? self::parseUrls($input['url'], $input['professional_imgs'], $input['slider_imgs']) : [];
            $reIndex = 1;
            $checkUpdateProfessionals = true;
            foreach ($urlDetails as $index => $urlDetail) {
                //update old professional
                $monthlyProfessionalId = (int) $input['monthlyProfessionalId'][$index];
                if (isset($monthlyProfessionalId)) {
                    $monthlyProfessional = MonthlyProfessional::find($monthlyProfessionalId);
                    if ($monthlyProfessional && isset($input['monthlyThemeSubjectId'])) {
                        $updateMonthlyProfessional = $monthlyProfessional->update([
                                                        'monthly_theme_subject_id' => $monthlyThemeSubjectId,
                                                        'url' => $urlDetail['url'],
                                                        'post_id' => $urlDetail['postId'],
                                                        'order' => $reIndex,
                                                        'professional_img' => !is_null($urlDetail['professionalImg']) ? self::uploadImg($urlDetail['professionalImg']) : $monthlyProfessional->professional_img,
                                                        'slider_img' => !is_null($urlDetail['sliderImg']) ? self::uploadImg($urlDetail['sliderImg']) : $monthlyProfessional->slider_img,
                                                    ]);
                        if (!$updateMonthlyProfessional) {
                            $checkUpdateProfessionals = false;
                        }
                    }
                //create new professional
                } else {
                    $createMonthlyProfessional = MonthlyProfessional::create([
                                                    'monthly_theme_subject_id' => $monthlyThemeSubjectId,
                                                    'url' => $urlDetail['url'],
                                                    'post_id' => $urlDetail['postId'],
                                                    'order' => $reIndex,
                                                    'professional_img' => !is_null($urlDetail['professionalImg']) ? self::uploadImg($urlDetail['professionalImg']) : '',
                                                    'slider_img' => !is_null($urlDetail['sliderImg']) ? self::uploadImg($urlDetail['sliderImg']) : '',
                                                ]);
                    if (!$createMonthlyProfessional) {
                        $checkUpdateProfessionals = false;
                    }
                }
                $reIndex++;
            }
            //delete professionals
            if (isset($input['removeMonthlyProfessionalId'])) {
                $deleteMonthlyProfessionals = MonthlyProfessional::whereIn('id', $input['removeMonthlyProfessionalId'])->delete();
                if (!$deleteMonthlyProfessionals) {
                    $checkUpdateProfessionals = false;
                }
            }
            if ($checkUpdateProfessionals) {
                DB::commit();
            } else {
                DB::rollback();
                $checkAllUpdate = false;
            }

            DB::beginTransaction();
            $checkUpdateThemes = true;
            //delete from monthly_themes and from monthly_theme_languages
            if (isset($input['removedMonthlyThemesId'])) {
                foreach ($input['removedMonthlyThemesId'] as $removedMonthlyThemeId) {
                    $deteleMonthlyTheme = MonthlyTheme::find((int) $removedMonthlyThemeId)->delete();
                    $deleteMonthlyThemeLanguage = MonthlyThemeLanguage::where('monthly_theme_id', $removedMonthlyThemeId)->delete();
                    $posts = Post::where('monthly_theme_id', $removedMonthlyThemeId);
                    $updatePosts = true;
                    if ($posts->count() > 0) {
                        $updatePosts = $posts->update(['monthly_theme_id' => null]);
                    }
                    if (!$deteleMonthlyTheme || !$deleteMonthlyThemeLanguage || !$updatePosts) {
                        $checkUpdateThemes = false;
                    }
                }
            }
            //update order in monthly_themes
            if (isset($input['monthlyThemesId'])) {
                foreach ($input['monthlyThemesId'] as $index => $monthlyThemeId) {
                    $monthlyTheme = MonthlyTheme::find((int) $monthlyThemeId);
                    if ($monthlyTheme) {
                        $updateMonthlyTheme = $monthlyTheme->update(['order' => $index + 1]);
                    }
                    if (!isset($updateMonthlyTheme)) {
                        $checkUpdateThemes = false;
                    }
                }
            }
            //update monthly_theme_languages and create new in monthly_themes
            $orderMonthlyTheme = isset($input['monthlyThemesId']) ? count($input['monthlyThemesId']) + 1 : 1;
            foreach ($input['theme_name']['vi'] as $index => $themeNameVi) {
                if (isset($input['theme_id']['vi'][$index])) {
                    $themeIdVi = (int) $input['theme_id']['vi'][$index];
                    $themeLanguageVi = MonthlyThemeLanguage::find($themeIdVi);
                    if ($themeLanguageVi) {
                        $updateThemeLanguageVi = $themeLanguageVi->update([
                            'name' => $input['theme_name']['vi'][$index],
                        ]);
                    }

                    $themeIdEn = (int) $input['theme_id']['en'][$index];
                    $themeLanguageEn = MonthlyThemeLanguage::find($themeIdEn);
                    if ($themeLanguageEn) {
                        $updateThemeLanguageEn = $themeLanguageEn->update([
                            'name' => $input['theme_name']['en'][$index],
                        ]);
                        $monthlyTheme = MonthlyTheme::find($themeLanguageEn->monthly_theme_id);
                        if ($themeLanguageEn) {
                            $updateMonthlyTheme = $monthlyTheme->update(['short_name' => self::convertToShortName($input['theme_name']['en'][$index])]);
                        }
                    }

                    $themeIdJa = (int) $input['theme_id']['ja'][$index];
                    $themeLanguageJa = MonthlyThemeLanguage::find($themeIdJa);
                    if ($themeLanguageJa) {
                        $updateThemeLanguageJa = $themeLanguageJa->update([
                            'name' => $input['theme_name']['ja'][$index],
                        ]);
                    } else {
                        // for update old theme with Japanese include recently
                        $updateThemeLanguageJa = MonthlyThemeLanguage::create([
                            'monthly_theme_id' => isset($monthlyTheme) ? $monthlyTheme->id: '',
                            'name' => $input['theme_name']['ja'][$index],
                            'language_code' => 'ja',
                        ]);
                    }


                    if (!isset($updateThemeLanguageVi) || !isset($updateThemeLanguageEn) || !$updateThemeLanguageJa || !isset($updateMonthlyTheme)) {
                        $checkUpdateThemes = false;
                    }
                } else {
                    $monthlyTheme = MonthlyTheme::create([
                        'short_name' => self::convertToShortName($input['theme_name']['en'][$index]),
                        'monthly_theme_subject_id' => $monthlyThemeSubject->id,
                        'order' => $orderMonthlyTheme,
                    ]);
                    if ($monthlyTheme) {
                        $orderMonthlyTheme++;
                        $monthlyThemeLanguageVi = MonthlyThemeLanguage::create([
                            'monthly_theme_id' => $monthlyTheme->id,
                            'name' => $input['theme_name']['vi'][$index],
                            'language_code' => 'vi',
                        ]);
                        $monthlyThemeLanguageEn = MonthlyThemeLanguage::create([
                            'monthly_theme_id' => $monthlyTheme->id,
                            'name' => $input['theme_name']['en'][$index],
                            'language_code' => 'en',
                        ]);
                        $monthlyThemeLanguageJa = MonthlyThemeLanguage::create([
                            'monthly_theme_id' => $monthlyTheme->id,
                            'name' => $input['theme_name']['ja'][$index],
                            'language_code' => 'ja',
                        ]);

                        if (!$monthlyThemeLanguageVi || !$monthlyThemeLanguageEn || !$monthlyThemeLanguageJa) {
                            $checkUpdateThemes = false;
                        }
                    }
                }
            }
            if ($checkUpdateThemes) {
                DB::commit();
            } else {
                DB::rollback();
                $checkAllUpdate = false;
            }
            return $checkAllUpdate;
        }
        return false;
    }

    public static function uploadImg($img)
    {
        $config = Config::get('image');
        $destinationPath = $config['theme_thumb']['upload_dir'];
        $imageName = sha1(time() . time() . mt_rand());
        $image = '';
        if (!is_null($img)) {
            $image = $destinationPath . '/' . $imageName;
            $img->move(public_path() . '/' . $destinationPath, $imageName);
        }
        return !empty($image) ? $image : null;
    }

    public static function validate($input)
    {
        $errors = [];
        if (is_null($input['subject_theme_name']) || empty($input['subject_theme_name'])) {
            $errors['empty_subject_name'] = trans('messages.theme.empty_subject_name');
        }
        $imageRules = Image::getUploadRules();
        $imageValidation = Validator::make($input, $imageRules);
        if ($imageValidation->fails()) {
            $errors['error_thumb_img'] = trans('messages.theme.error_thumb_img', ['size' => Config::get('image')['max_image_size'], 'type' => 'jpg, jpeg, png, gif']);
        }
        $input['professional_imgs'] = isset($input['professional_imgs']) ? $input['professional_imgs'] : null;
        $input['slider_imgs'] = isset($input['slider_imgs']) ? $input['slider_imgs'] : null;

        if (isset($input['theme_name'])) {
            $checkThemeThisMonth = false;
            $lowerCaseThemeVi = [];
            $lowerCaseThemeEn = [];
            foreach ($input['theme_name']['vi'] as $index => $themeNameVi) {
                if (empty($input['theme_name']['vi'][$index]) || empty($input['theme_name']['en'][$index])) {
                    $checkThemeThisMonth = true;
                }

                $lowerCaseThemeVi[] = strtolower($input['theme_name']['vi'][$index]);
                $lowerCaseThemeEn[] = strtolower($input['theme_name']['en'][$index]);
            }
            if ($checkThemeThisMonth) {
                $errors['empty_theme_this_month'] = trans('messages.theme.empty_theme_this_month');
            }

            $countThemeNameVi = array_count_values($lowerCaseThemeVi);
            $checkDuplicateVi = false;
            foreach ($countThemeNameVi as $key => $duplicate) {
                if ($duplicate > 1) {
                    $checkDuplicateVi = true;
                }
            }
            if ($checkDuplicateVi) {
                $errors['duplicate_theme_name_vi'] = trans('messages.theme.duplicate_theme_name_vi');
            }

            $countThemeNameEn = array_count_values($lowerCaseThemeEn);
            $checkDuplicateEn = false;
            foreach ($countThemeNameEn as $key => $duplicate) {
                if ($duplicate > 1) {
                    $checkDuplicateEn = true;
                }
            }
            if ($checkDuplicateEn) {
                $errors['duplicate_theme_name_en'] = trans('messages.theme.duplicate_theme_name_en');
            }
        } else {
            $errors['empty_theme_this_month'] = trans('messages.theme.empty_theme_this_month');
        }
        return $errors;
    }

    public static function monthYearOptions()
    {
        $months = [0 => trans('labels.select_month')];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = trans('datetime.month.' . $i);
        }
        $years = [0 => trans('labels.select_year')];
        foreach (range(2014, (date('Y') + 1)) as $year) {
            $years[$year] = $year;
        }
        return [
            'months' => $months,
            'years' => $years,
        ];
    }

    public static function convertToShortName($name)
    {
        $shortName = vn_to_latin($name, true);
        $shortName = replace_special_characters($shortName, '_');
        return $shortName;
    }

    public static function getOptionMonthlyThemes($monthlyThemes, $themeId)
    {
        $monthlyThemeLanguages = [];
        foreach ($monthlyThemes as $key => $monthlyTheme) {
            $monthlyThemeLanguage = $monthlyTheme->themeLanguages()->first();
            $monthlyThemeLanguages[$monthlyThemeLanguage->monthly_theme_id] = $monthlyThemeLanguage->name;
        }
        $html = \Form::select('monthly_theme_id', [0 => trans('messages.theme.select_theme_in_month')] + $monthlyThemeLanguages, isset($themeId) ? $themeId : null, ['id' => 'monthly-theme-id']);
        return $html;
    }
}
