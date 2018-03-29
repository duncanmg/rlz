// rl/charmap/charmap.phtml
$(".charmap_button_div").find(".btn").click(function () {
    var a = $("[name=answer]");
    a.val(a.val() + this.innerHTML);
    a.focus();
    return false;
});
