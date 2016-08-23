var home = function (message) {
    this.init();
    this.loading = message.loading;
    this.loaded = message.loaded;
};

home.prototype = {
    init: function () {
        this.loadmoreId = $('#load_more_question');
        this.pageCount = 2;
        this.listQuestion = $('.faq-list');
        this.tabMonth = $('.month');
        this.tabOver = $('.over');
    },

    onClickLoadMore: function () {
        var json = {
            'pages': this.pageCount
        };
        this.loadMoreUrl('GET', '/faq/ajax/question', json);
    },

    onClickTabRank: function (id) {
        var json = {
            'tabs': id
        };
        this.getUserRanking('GET', '/faq/ajax/user-ranking', json, id);
    },

    getUserRanking: function (method, url, data, id) {
        this.origin = id;
        this.id = $('.' + id);
        var obj = this;
        $.ajax({
            method: method,
            url: url,
            data: data,
            beforeSend: function () {

            }
        }).done(function (response) {
            obj.id.html(response);
            if (obj.origin == 'thisMonth') {
                obj.tabMonth.removeAttr('onclick');
            }
            if (obj.origin == 'Overall') {
                obj.tabOver.removeAttr('onclick');
            }
        });
    },

    loadMoreUrl: function (method, url, data) {
        var obj = this;
        $.ajax({
            method: method,
            url: url,
            data: data,
            beforeSend: function () {
                obj.loadmoreId.text(obj.loading);
            }
        }).done(function (response) {
            if (!response) {
                obj.loadmoreId.remove();
            }
            obj.pageCount++;
            obj.loadmoreId.text(obj.loaded);
            obj.listQuestion.append(response);
        });
    }
};