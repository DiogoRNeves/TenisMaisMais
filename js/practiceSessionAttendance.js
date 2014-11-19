/**
 * Created by diogoneves on 15/11/14.
 */

$(function() {
    $('.hide').show();
    $(".auto-submit-item").change(function(event) {
        $('#PracticeSessionHistoryRegistryForm_autoSubmit').prop('checked', true);
        $('#PracticeSessionHistoryRegistryForm_clickedCancel').prop('checked', event.target.id === 'PracticeSessionHistoryRegistryForm_cancelled');
        $("form").submit();
    });
    $(":submit").click(function() {
        $('#PracticeSessionHistoryRegistryForm_autoSubmit').prop('checked', false);
    });
});