function savecontenteaip( arptident,cateid,val,seq )
{
    let catId = Number( cateid )
    let seqq = 0
    let pathdetail = "{{URL::to('/')}}/eaip/temp";
    console.log( pathdetail, arptident, val );
    $.ajax({
        url: pathdetail,
        data: {arpt_ident : arptident,category_id:catId},
        type: "json",
        method: "GET",

        success: function ( result )
        {
            console.log( 'result.data', result.data );
            if ( result.data.length == 0 ) {
                var newdata = { arpt_ident: arptident, category_id: catId, content: val, seq: seqq, status: 'R' }
                // $.ajax( {
                //     url: "{{ URL::to('/') }}/api/eaip/temp/save",
                //     type: "POST",
                //     data: JSON.stringify( newdata ),
                //     cache: false,
                //     contentType: 'application/json; charset=utf-8',
                //     processData: false,
                //     success: function ( response )
                //     {
                //         Swal.fire(
                //             'Updates!',
                //             'Raw Data Status has been updated.',
                //             'success'
                //         );
                //     }
                // } );
            }
        }
    })
      
    
    // if ( seq == 0 ) {
    //     console.log('savecontenteaip',arptident,seq,cateid,val)
    //     this.SavetoReqestDataRwwdata(arptident)
    // }
    // save to rawdata_pub, utk pengecekan request perubahan data
    // setTimeout( () =>
    // {
        
    // },500)

    
}