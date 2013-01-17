function OnLoad(title , urllink , pos , count) {
      
    var feeds = [
    {
        title: title,
        url: urllink
    },
        
    ];
    if(pos == 'v'){
        var vertical = true;
        var horizontal = false;
    }else{
        var vertical = false;
        var horizontal = true;
    }
   var options = {
        stacked : vertical,
        horizontal : horizontal,
        title : title,
        numResults : count
    };
     
   
    
    new GFdynamicFeedControl(feeds, 'ajaxFeed', options);
}