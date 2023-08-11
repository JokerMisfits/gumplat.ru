
<?php
/** @var yii\web\View $view */
/** @var app\models\Cities $model */

$modelJson = json_encode($model, JSON_PRETTY_PRINT);
unset($model);

$view->registerJs(<<<JS
    var model = $modelJson;
JS, $view::POS_END);

$js = <<< JS
ymaps.ready(function (){
    var map = new ymaps.Map('map', {
        center: [55.755814, 37.617635], //Москва
        zoom: 4
    });
    var placemarks = [];
    let count = model.length;
    for(var i = 0; i < count; i++){
        placemarks[i] = {
            coords: [model[i].y, model[i].x], 
            content: model[i].name};
    }

    for(var i = 0; i < placemarks.length; i++){
        var placemark = placemarks[i];
        var marker = new ymaps.Placemark(placemark.coords, {
            hintContent: placemark.content
        });
        map.geoObjects.add(marker);
    }
});
JS;
$view->registerJs($js, $view::POS_LOAD);
?>