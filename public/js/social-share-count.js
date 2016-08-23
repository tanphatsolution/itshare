$(function() {
    getSocialShareCount(shareCountUrl, 'facebook');
    getSocialShareCount(shareCountUrl, 'google');
});

function getSocialShareCount(url, provider) {
    $.ajax({
        type: 'GET',
        url: baseURL + '/api/get_social_count',
        data: {
            'url': url,
            'provider': provider
        },
        success: function(response) {
            if (!response.error) {
                var data = response.data,
                    shareCount = shortenTotal(data.share_count);

                switch (data.provider) {
                    case 'facebook':
                        $('.face-share-count').html(shareCount);
                        break;
                    case 'twitter':
                        $('.tweet-share-count').html(shareCount);
                        break;
                    case 'google':
                        $('.google-share-count').html(shareCount);
                        break;
                }
            }
        },
        error: function() {
        }
    });
}

function shortenTotal(count, digits) {
    var symbolArr = [
        {value: 1E18, symbol: "E"},
        {value: 1E15, symbol: "P"},
        {value: 1E12, symbol: "T"},
        {value: 1E9, symbol: "G"},
        {value: 1E6, symbol: "M"},
        {value: 1E3, symbol: "k"}
    ];
    var length = symbolArr.length;
    for (var i = 0; i < length; i++) {
        if (count >= symbolArr[i].value) {
            return (count / symbolArr[i].value).toFixed(digits).replace(/\.?0+$/, "") + symbolArr[i].symbol;
        }
    }

    return count;
}