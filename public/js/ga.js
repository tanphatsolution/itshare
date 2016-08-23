var _ga_url = {
  conf : {
    f : ["facebook", "social"],
    g : ["facebook_group", "social"],
    a : ["facebook_ad", "social"],
    t : ["twitter", "social"]
  },

  getQuery : function(name) {
    if(location.search) {
      var query = location.search;
      query = query.substring(1,query.length);
      var qArray = [];
      qArray = query.split("&");
      for(var i=0;i<qArray.length;i++) {
        var param = qArray[i].split("=");
        if(param[0] == name){
          return param[1];
        }
      }
    }
  },

  get_param : function(str){
    var param_str = this.getQuery(str)
    return "&utm_campaign=" + param_str
  },

  main : function() {
    for (var i in this.conf) {
      if((!(location.search).match(/utm_source/)) && this.getQuery(i)) {
        var str = "&utm_source=" + this.conf[i][0] + "&utm_medium=" + this.conf[i][1] + this.get_param(i);
        var url_str = document.URL + str;
        location.href = url_str;
      }
    }
  }
}
_ga_url.main();