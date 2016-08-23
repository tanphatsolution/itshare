$(function() {
    $('.process-html-content').processText();
    markdownProcessLinkTarget('process-html-content');
});

function markdownProcessLinkTarget(markdownContentClassName) {
    var linkTarget = $('.' + markdownContentClassName).find('a');
    linkTarget.each(function () {
       $(this).attr("target", '_blank');
    });
}