<?php

/** @var yii\web\View $this */

use app\models\Prefectures;
use app\models\Years;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <?= Html::dropDownList(
                    'prefecture',
                    1,
                    ArrayHelper::map(Prefectures::find()->orderBy('prefecture_id')->all(), 'prefecture_id', 'prefecture_name'),
                    [
                        'prompt' => "Prefecture",
                        'class' => 'form-control',
                        'id' => 'prefecture',
                    ]
                ) ?>
            </div>
            <div class="col-lg-4 mb-3">
                <?= Html::dropDownList(
                    'year',
                    1,
                    ArrayHelper::map(Years::find()->orderBy('year_id')->all(), 'year_id', 'year'),
                    [
                        'prompt' => "Year",
                        'class' => 'form-control',
                        'id' => 'year',
                    ]
                ) ?>
                <br>
                <?= Html::label('No data available', null, ['class' => 'form-control', 'id' => "populationResult"]) ?>
            </div>
            <div class="col-lg-4 mb-3">
                <?= Html::button(
                    'Get Population',
                    [
                        'class' => 'btn btn-success',
                        'id' => 'load_button',
                    ]) ?>
                <br>
                <br>
                <form id="fileUploadForm">
                    <?= Html::fileInput('data', null, ['id' => 'fileInput', 'class' => 'form-control',]) ?>
                    <?= Html::submitButton('Send File', ['id' => 'uploadButton', 'class' => 'form-control',]) ?>
                </form>
                <br>
                <form id="urlUploadForm">
                    <?= Html::input('text', 'url', null,['id' => 'urlInput', 'class' => 'form-control', 'placeholder' => "Type CVS downloading URL",]) ?>
                    <?= Html::submitButton('Send Url', ['id' => 'uploadUrlButton', 'class' => 'form-control',]) ?>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-3">
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        // get population function
        $('#load_button').on('click', function () {
            let selectedPrefecture = $('#prefecture').val();
            let enteredYear = $('#year').val();
            $.ajax({
                url: 'site/population',
                type: 'GET',
                data: {
                    prefecture: selectedPrefecture,
                    year: enteredYear
                },
                dataType: 'json',
                success: function (data) {
                    let population = data.population;
                    let text = 'Population: ' + population;
                    if (population === 0) {
                        text = 'No data available';
                    }
                    $('#populationResult').text(text);
                },
                error: function () {
                    alert("APi Error");
                }
            });
        });

        $(document).on('submit', '#fileUploadForm', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: 'site/upload',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    location.reload();
                    console.log('Success upload file by Ajax');
                },
                error: function () {
                    console.log('Upload file Failed');
                    alert("Upload Failed")
                }
            });
        });

        $(document).on('submit', '#urlUploadForm', function (e) {
            e.preventDefault();
            let cvsUrl = $('#urlInput').val();

            $.ajax({
                type: 'GET',
                url: 'site/load-by-url',
                data: {
                    url: cvsUrl,
                },
                dataType: 'json',
                success: function () {
                    location.reload();
                    console.log('Success upload by url');
                },
                error: function () {
                    console.log('Upload by url Failed');
                    alert("Upload Failed")
                }
            });
        });
    })

</script>
