<script type="text/javascript">
    var baseURL = "{{ URL::to('/') }}";
    var quickSearchType = {{ json_encode(App\Services\SearchService::elementQuickSearchTypes(), TRUE) }};
    @if (isset($currentUser) && $currentUser)
        var userRedisChannel = {{ json_encode(App\Services\RedisService::getUserChannel($currentUser->id)) }};
        var socketIoPort = {{ \Config::get('app.socket_io_port')}};
    @endif
    var MAX_IMAGE_SIZE = {{ \Config::get('image.max_image_size') / 1000 }};
</script>
