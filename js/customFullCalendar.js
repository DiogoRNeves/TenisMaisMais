function convertRequiredFields(eventData, start, end) {
    eventData.startTime = start;
    eventData.endTime = end;
    eventData.dayOfWeek = start.day();
    eventData.editable = true;
    return eventData;
}

function dSelect(start, end) {
    var eventData = {};
    var eventData = convertRequiredFields({start: 0}, start, end);
    showForm(eventData, false);
    $("#calendar").fullCalendar("unselect");
}

function populateForm(form, event) {
    var coachIDelement = form.find("[id$='coachID']");
    var coachID = coachIDelement.select2('val');
    form.find('form').not('.ignoreField').val('').removeAttr('checked').removeAttr('selected');
    form.find(':input').not('.ignoreField').each(function() {
        updateValue($(this), null);
    });
    for (var key in event) {
        var obj = event[key];
        if (key.charAt(0) !== '_' && key !== 'className') {
            var field = form.find("[id$='" + key + "']");
            updateValue(field, obj);
        }
    }
    updateValue(coachIDelement, coachID);
}

function updateValue(field, obj) {
    if (field !== null) {
        try {
            field.val(obj.format('H:mm'));
        } catch (err) {
            if (isSelect2(field)) {
                field.select2('val', obj);
            } else {
                field.val(obj);
            }
        }
    }
}

function isSelect2(field) {
    var classStart = 'select2';
    try {
        return field.attr('class').substr(0, classStart.length) === classStart;
    } catch (err) {
        return false;
    }
}

function showForm(event, deleteButtonVisible) {
    showDeleteButton(deleteButtonVisible);
    var $modal = $('#modalDialog');
    if (event.editable) {
        $('.modal-footer').show();
    } else {
        $('.modal-footer').hide();
    }
    populateForm($modal, event);
    $modal.modal('show');
}

//not in use, compatibility issues?
function dEventDataTransform(event) {
    event.start = event.startTime;
    event.end = event.endTime;
    event.title = 'ID: ' + event.practiceSessionID;
    return event;
}

function deleteObject() {
    var id = $('form:first').find("[id$='ID']:first").val();
    var url = $('[data-toggle="confirmation"]').attr('data-baseUrl') + "/" + id;
    $.post(
            url,
            null,
            function(response) {
                if (response.status === 400) {
                    $('#calendar').fullCalendar('refetchEvents');
                    $('#modalDialog').modal('hide');
                }
            }
    ).fail(function(jqXHR, textStatus, errorThrown) {
        alert('Failed to connect with server! \n' + errorThrown + ': ' + textStatus);
    });
}

function dEventChange(event, revertFunc) {
    convertRequiredFields(event, event.start, event.end);
    var $modal = $('#modalDialog');
    populateForm($modal, event);
    doAjaxPost(function(response) {
        if (response.status !== 400) {
            alert('Could not change event!');
            revertFunc();
        }
    });
}

function doAjaxPost(handleResponse) {
    var form = $('#modalDialog').find('form:first');
    var data = form.serialize();
    $.post(
            form.attr('action'),
            data,
            function(response) {
                handleResponse(response);
            }
    ).fail(function(jqXHR, textStatus, errorThrown) {
        alert('Failed to connect with server! \n' + errorThrown + ': ' + textStatus);
    });
}

function showDeleteButton(visibility) {
    var deleteButton = $(".deleteObject:first");
    if (visibility) {
        deleteButton.show();
    } else {
        deleteButton.hide();
    }
}

$(function() {

    $('form:first').on('submit', function(jsEvent) {
        jsEvent.preventDefault();
        doAjaxPost(function(response) {
            if (response.status === 400) {
                $('#calendar').fullCalendar('refetchEvents');
                $('#modalDialog').modal('hide');
            } else {
                alert(response.message + ': \n' + response.status + ': ' + response.errors);
            }
        });
    });

    $(".submitModal").on('click', function() {
        $('form:first').submit();
    });

    $('[data-toggle="confirmation"]').confirmation({
        onConfirm: function() {
            deleteObject();
        }
    });
});