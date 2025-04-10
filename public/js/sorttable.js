
$.tablesorter.addParser({ 
        // set a unique id 
        id: 'date', 
        is: function(s) { 
            // return false so this parser is not auto detected 
            return false; 
        }, 
        format: function(s) { 
            // format your data for normalization 
            var date = s.split('-');
            var hash = date[2] + date[1] + date[0];
            return hash;
        }, 
        // set type, either numeric or text 
        type: 'numeric' 
    }); 

function tablesort(){
    var lista = $("#lista");
    if(lista.length != 0 ){
        lista.tablesorter(
            {
                widthFixed: true,
                //widgets: ['zebra'],
                headers: {
                    4:{
                        sorter: 'date'
                    },
                    6: {
                        sorter: false
                    } 
                }
                
            }
        )
        
    }
}