
        <footer class="text-center" id="footer">
            &copy; 2019 Copyright: <b>RhemaBooks<b> &trade;
        </footer>


        

    <script>
        jQuery(window).scroll(function(){
            var vscroll = jQuery(this).scrollTop();
        })

        function detailsmodal(id){
            var data = {"id": id};
            jQuery.ajax({
                url: '/rhemabooks/includes/detailsmodal.php',
                method: "post",
                data: data,
                success: function(data){
                    jQuery('body').append(data);
                    jQuery('#details-modal').modal('toggle');
                },
                error: function(){
                    alert("something went wrong!");
                }
            });
        } 
    </script> 

</body>
</html>