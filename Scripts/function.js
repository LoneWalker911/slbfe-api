function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
function eraseCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function notifySuccess(message) {
    iziToast.success({
        title: '',
        message: typeof message === 'undefined' ? 'Data has been updated successfully!' : message,
        position: 'topCenter',
        color: 'green',
        timeout: 2500,
        progressBar: false
    });
}
function notifyError(message) {
    iziToast.error({
        message: typeof message === 'undefined' ? 'An error occurred!' : message,
        position: 'topCenter',
        timeout: 2500,
        progressBar: false
    });
}
function logout() {
    eraseCookie('token');
    window.location.href = "/Login";

}