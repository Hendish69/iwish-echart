// (function( $ ){
//     $.fn.myfunction = function() {
//        alert('hello world');
//        return this;
//     }; 
//  })( jQuery );
//  $('#my_div').myfunction();

// Number.prototype.pad = function(n) {
//     return new Array(n).join('0').slice((n || 2) * -1) + this;
// }
(function ( $ ) {
	
    $.fn.alterClass = function ( removals, additions ) {
        
        var self = this;
        
        if ( removals.indexOf( '*' ) === -1 ) {
            // Use native jQuery methods if there is no wildcard matching
            self.removeClass( removals );
            return !additions ? self : self.addClass( additions );
        }
    
        var patt = new RegExp( '\\s' + 
                removals.
                    replace( /\*/g, '[A-Za-z0-9-_]+' ).
                    split( ' ' ).
                    join( '\\s|\\s' ) + 
                '\\s', 'g' );
    
        self.each( function ( i, it ) {
            var cn = ' ' + it.className + ' ';
            while ( patt.test( cn ) ) {
                cn = cn.replace( patt, ' ' );
            }
            it.className = $.trim( cn );
        });
    
        return !additions ? self : self.addClass( additions );
    };
    
    })( jQuery );

(function( $ ){
    $.fn.select2me = function(subsel_id,url) {
        var self = this;
        let x = self.val(); 
        self.off('change').on('change',function() {
            x = self.val();
            $.ajax({
                url: url + x,
                type: "json",
                method: "get",
                success: function(result){
                    if(result.status=='success'){
                        $('#'+subsel_id).empty();
                        $('#'+subsel_id).append("<option selected>Select Subsection</option>");
                        $.each(result.data, function(k,v) {
                            $('#'+subsel_id).append("<option value="+v.id+">"+v.sub_id + ' ' + v.definition+"</option>") 
                        });
                    }
                }
            }); 
        }); 
    }; 
 })( jQuery );
var days = function(year, month) {
    return new Date(year, month, 0).getDate();
}