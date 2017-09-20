<?php

$dayOffInsert = "<form name='day-off-insert-form' method='post' action='?'>
  <input type='hidden' name='staff_id' value='$staff->id'>
  <fieldset>
    <legend>សុំច្បាប់</legend>
    <table id='day-off-insert-table' style='display: table'>
      <tr>
        <td><label for='name'>ឈ្មោះពេញ</label></td>
        <td>$staff->name</td>
      </tr>
      <tr>
        <td><label for='from_date'>ចាប់ពីថ្ងៃ</label></td>
        <td><input type='text' id='from_date' name='from_date' class='_input' required placeholder='YYYY-MM-DD'
          pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' title='YYYY-MM-DD' autofocus></td>
      </tr>
      <tr>
        <td><label for='to_date'>ទៅដល់ថ្ងៃ</label></td>
        <td><input type='text' id='to_date' name='to_date' class='_input' required placeholder='YYYY-MM-DD'
          pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' title='YYYY-MM-DD'></td>
      </tr>
      <tr>
        <td><label for='description'>សេចក្ដីពិពណ៌នា</label></td>
        <td><textarea rows='3' id='description' name='description' class='_input' required></textarea></td>
      </tr>
      <tr><td colspan='2'><button id='submit' type='submit'>បញ្ជូន</button></td></tr>
    </table>
  </fieldset>
</form>";

$section = <<<"SECTION"
$dayOffInsert
SECTION;

$js = <<<"JS"
<script>
$('input#from_date').on('focusout', function() {
    var from_date = new Date($(this).val());
    if (from_date.toDateString() == "Invalid Date") {
        $(this).val('');
        $(this).focus();
    } else {
        $('input#to_date').on('focusout', function() {
            var to_date = new Date($(this).val());
            if (to_date.toDateString() == "Invalid Date" ||
                to_date < from_date) {
                $(this).val('');
                $(this).focus();
            }
        });
    }
});
</script>
JS;

require __DIR__ . '/_base_/_base_.html.php';
