function required(idElement, idFeedback = '', invalidMessage = '') {
    console.log($('#' + idElement).parent());
    console.log($('#' + idElement).prop('type'));
}