<div>
    <input type="text" 
           <?php foreach($field->getAttributes() as $key=>$val): ?>
               <?=$key?>="<?=$val?>"
           <?php endforeach;?>
               value="<?=$field->getValue()?>"
           >
</div>
<script>

</script>