function actionConfirm(text, url) {
  if (confirm(text)) {
    location.href = url;
  }
}
