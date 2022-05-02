$("button").click(function () {
    $.post('https://edu.santhawa.lk/apiv1/citizen', { email: 'thi@jkl.lk' }, function (response) {
        console.log(response);
        alert("success");
    });
});