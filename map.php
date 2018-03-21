<div id='map' style='width: 797px; height: 400px; border: 1px solid black;'></div>
    <script type="text/javascript">
        ymaps.ready(init);
        var myMap, 
            myPlacemark;

        function init(){ 
            myMap = new ymaps.Map("map", {
                center: [<?php echo $getplacemark; ?>],
                zoom: 7
            }); 
            
            myPlacemark = new ymaps.Placemark([<?php echo $getplacemark; ?>], {
                hintContent: '<?php echo $getaddress; ?>',
                balloonContent: '<?php echo $getaddress; ?>'
            });

            myMap.geoObjects.add(myPlacemark);
        }
    </script>