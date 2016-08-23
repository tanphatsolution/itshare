<div class="modal fade" id="modalUserStock">
  <div class="modal-dialog modal-inner">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('buttons.close') }}</span></button>
        <h4 class="modal-title">{{ trans('modals.user_stock.title') }}</h4>
      </div>
      <div class="modal-body">
        <ul id="userStockList">
          @include('user._list_users_stock', ['userStocks' => $userStocks])
        </ul>
          @if ($userStocks->count() >= App\Data\Blog\Post::USER_STOCK_PERPAGE)
            <p class="text-center see-more">
            <a  id="seeMoreUserStock"
                class="btn btn-link"
                data-post="{{ $postId }}"
                data-start="{{ App\Data\Blog\Post::USER_STOCK_PERPAGE }}"
                data-increase="{{ App\Data\Blog\Post::USER_STOCK_PERPAGE }}"
                data-message="{{ trans('messages.loading') }}">{{ trans('labels.see_more') }}</a>
            </p>
          @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('buttons.close') }}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
