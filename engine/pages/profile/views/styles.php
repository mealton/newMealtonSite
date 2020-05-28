<div class="align">
    <button type="button" class="btn style <?=$css['text-align'] == 'left' ? ' active' : ''?>" data-prop="text-align" data-style="left"><i class="fa fa-align-left" aria-hidden="true"></i></button>
    <button type="button" class="btn style <?=$css['text-align'] == 'center' ? ' active' : ''?>" data-prop="text-align" data-style="center"><i class="fa fa-align-center" aria-hidden="true"></i></button>
    <button type="button" class="btn style <?=$css['text-align'] == 'right' ? ' active' : ''?>" data-prop="text-align" data-style="right"><i class="fa fa-align-right" aria-hidden="true"></i></button>
    <button type="button" class="btn style <?=$css['text-align'] == 'justify' ? ' active' : ''?>" data-prop="text-align" data-style="justify"><i class="fa fa-align-justify" aria-hidden="true"></i></button>
</div>
<div class="font-style">
    <button type="button" class="btn style <?=$css['font-weight'] == 'bold' ? ' active' : ''?>" data-prop="font-weight" data-style="bold"><i class="fa fa-bold" aria-hidden="true"></i></button>
    <button type="button" class="btn style <?=$css['font-style'] == 'italic' ? ' active' : ''?>" data-prop="font-style" data-style="italic"><i class="fa fa-italic" aria-hidden="true"></i></button>
    <button type="button" class="btn style <?=$css['text-decoration'] == 'underline' ? ' active' : ''?>" data-prop="text-decoration" data-style="underline"><i class="fa fa-underline" aria-hidden="true"></i></button>
    <!--<button type="button" class="btn style" data-prop="cancel" title="Отменить"><i class="fa fa-ban" aria-hidden="true"></i></button>-->
</div>
<div class="font-size">
    <button type="button" class="btn style <?= intval($css['font-size']) <= 14 ? ' disabled' : ''?>" data-prop="font-size" data-style="less"><i class="fa fa-caret-down" aria-hidden="true"></i></button>
    <input type="number" class="form-control" name="set-font-size" value="<?=$css['font-size'] ? intval($css['font-size']) : 16?>" min="14" max="36" readonly>
    <button type="button" class="btn style <?= intval($css['font-size']) >= 36 ? 'disabled' : ''?>" data-prop="font-size" data-style="larger"><i class="fa fa-caret-up" aria-hidden="true"></i></button>
</div>

