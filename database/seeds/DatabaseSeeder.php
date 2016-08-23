<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call('UserTableSeeder');

        $this->call('RoleTableSeeder');

        $this->call('UserRoleTableSeeder');

        $this->call('PermissionTableSeeder');

        $this->call('RolePermissionTableSeeder');

        $this->call('CategoryTableSeeder');

        $this->call('SkillCategorySeeder');

        $this->call('DomainTableSeeder');

        $this->call('ContestTableSeeder');

        $this->call('ContestDomainTableSeeder');

        $this->call('ContestCategoryTableSeeder');

        $this->call('QuestionTableSeeder');

        $this->call('QuestionCategoryTableSeeder');
        
        $this->call('RequestUserAnswerTableSeeder');

        $this->call('AnswerTableSeeder');

        $this->call('AnswerHelpfulTableSeeder');

        $this->call('ClipTableSeeder');

        $this->call('RequestDetailQuestionTableSeeder');
        
        $this->call('AnswerDetailQuestionTableSeeder');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Model::reguard();
    }

}
