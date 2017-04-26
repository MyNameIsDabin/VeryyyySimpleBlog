function formatFileSize(bytes) {
  var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  if (bytes == 0) return '0 Byte';
  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
  return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function trimTextArea(area) {
  area.normalize();
}

//없앨 태그의 id
function unwrapTagById(id) {
  var remove = document.getElementById(id);
  if (remove) {
    var fragment = document.createDocumentFragment();
    while(remove.firstChild) {
      fragment.appendChild(remove.firstChild);
    }
    remove.parentNode.replaceChild(fragment, remove);
  }
}

function fixedSelectionRange(areaElement) {
  if (window.getSelection) {
    var fixed = document.getElementById('fixedSelectionRange');
    var range = window.getSelection().getRangeAt(0);
    var start = range.startOffset;
    var end = range.endOffset;
    if (fixed) {
      unwrapTagById('fixedSelectionRange');
    }
    var storage = document.createElement('span');
    storage.setAttribute('id', 'fixedSelectionRange');
    range.surroundContents(storage);
    trimTextArea(areaElement);
  }
}

function focusFixedSelectionRange(areaElement) {
  if (window.getSelection) {
    areaElement.focus();
    var range = document.createRange();
    var fixed = document.getElementById('fixedSelectionRange');
    if (fixed) {
      range.selectNode(fixed);
      var selection = window.getSelection();
      selection.removeAllRanges();
      selection.addRange(range);
      range.deleteContents();
    }
  }
}

function insertTextAtCursor(text) {
    if (window.getSelection) {
      var sel = window.getSelection();
      var range = sel.getRangeAt(0);
      range.insertNode(document.createTextNode(text));
      range = text;
    }
}

function insertImageAtCursor(img) {
  if (window.getSelection) {
    var sel = window.getSelection();
    var range = sel.getRangeAt(0);
    var obj = document.createElement('img');
    obj.setAttribute('src', img);
    range.insertNode(obj);
    range = img;
  }
}

function replaceToActureImg(textArea) {

}

function openPopUpById(id) {
  var popup = document.getElementById(id);
  popup.style.display = "block";
}

function closePopUpById(id) {
  var popup = document.getElementById(id);
  popup.style.display = "none";
}

function openCommonPopUp(title, message) {
  var _title = document.getElementById('common-popup-title').firstChild;
  var _message = document.getElementById('common-popup-message').firstChild;
  _title.nodeValue = title;
  _message.nodeValue = message;
  openPopUpById('common-popup');
}

/*
  form에 URL(action)을 변경하고, hidden 속성의 엘리먼트들을 추가할 수 있다.
  ex 1)setFormActionWithData('myform', 'sample.com', 'data-key', 'data-value');
  ex 2)setFormActionWithData('myform', 'sample.com');
*/
function setFormActionWithData(myform, action) {
  var form = document.getElementById(myform);
  if (arguments.length > 2) {
    for(var i=2; i<arguments.length-1; i+=2) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = arguments[i];
      input.value = arguments[i+1];
      form.appendChild(input);
    }
  }
  form.setAttribute("action", action);
}

/*
  url로 이동한다. Post 타입으로 데이터 전달이 가능.
  ex 1)moveUrlWithPost('sample.com', 'data-key', 'data-value');
  ex 2)moveUrlWithPost('sample.com');
*/
function moveUrlWithPost(url) {
  var form = document.createElement('form');
  form.setAttribute("method", "post");
  form.setAttribute("action", url);
  form.setAttribute("target", "_self");
  document.body.appendChild(form);

  if (arguments.length > 1) {
    for(var i=1; i<arguments.length-1; i+=2) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = arguments[i];
      input.value = arguments[i+1];
      form.appendChild(input);
    }
  }

  form.submit();
}
