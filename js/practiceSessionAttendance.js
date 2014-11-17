/**
 * Created by diogoneves on 15/11/14.
 */

$(function() {
    $('.hide').show();
    $(".auto-submit-item").change(function() {
        $('#PracticeSessionHistoryRegistryForm_autoSubmit').prop('checked', true);
        $("form").submit();
    });
    $(":submit").click(function() {
        $('#PracticeSessionHistoryRegistryForm_autoSubmit').prop('checked', false);
    });
});