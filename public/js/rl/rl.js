// rl/question/index.phtml
 function checkForm()
  {
      document.getElementsByName('answer')[0].value = 'Do not know';
      return true;
  }

// rl/charmap/charmap.phtml
$(".charmap_button_div").find(".btn").click(function () {
        var a = $("[name=answer]");
        a.val(a.val()+ this.innerHTML);
        a.focus();
        return false;
});
