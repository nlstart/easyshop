<!--
function enabledJS()
{
var url="http://"+location.hostname+"/e107_plugins/easyshop/includes/enabledJS.php";
  new Ajax.Request(url, {
  onSuccess: function(transport) {
  }})
} 

function ES_do_ajax(source, target, arg)
{
    url = "http://"+location.hostname+"/e107_plugins/easyshop/track_checkout.php?source="
    +source+"&target="
    +target+"&arg="+arg;
     
    new Ajax.Request(url, {
    method:'get',
	onSuccess: function(transport) {
                var text = transport.responseText;
                var startMenu = text.indexOf("<menu_name>"); // the target DIV
                var endMenu = text.indexOf("</menu_name>");
                var startSource = text.indexOf("<source_id>"); // the source DIV
                var endSource = text.indexOf("</source_id>");
                var startArg = text.indexOf("<arg>");  // the argument
                var endArg = text.indexOf("</arg>");                
                var finaltext = text.slice(endArg+6);
                var source_id = text.slice(startSource+11,endSource);
                var target_menu = text.slice(startMenu+11,endMenu);
                var menu_arg = text.slice(startArg+5,endArg);
                                                
                document.getElementById(target_menu).innerHTML=finaltext;
                var test = document.getElementById(target_menu);
                document.getElementById(source_id).innerHTML="";
                var test2 = document.getElementById(source_id);
                alert (finaltext);
            }
            });    
}
//-->