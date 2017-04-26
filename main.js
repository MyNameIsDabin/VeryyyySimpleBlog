
//로그인 팝업 닫기버튼
var closeButton = document.getElementById('login-popup-close-btn');
if (closeButton) {
  closeButton.addEventListener('click', function(event){
    closePopUpById('login-popup');
  })
};

//로그인 버튼
var loginButton = document.getElementById('btn-login');
if (loginButton) {
  loginButton.addEventListener('click', function(event){
    var data = 'email='+document.getElementById('login-form-email').value;
    data += '&password='+document.getElementById('login-form-password').value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/blog/process/process-login.php');
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(data);
    xhr.onreadystatechange = function(){
      if(xhr.readyState === 4 && xhr.status === 200){
        // openCommonPopUp('디버그', xhr.responseText);
        var data = JSON.parse(xhr.responseText);
        if (data.resultCode == 0) {
          location.href="/blog/index.php";
        } else {
          closePopUpById('login-popup');
          openCommonPopUp('로그인', data.resultMessage);
        }
      }
    }
  })
};

//댓글 더 보기 버튼
var moreCommentButton = document.querySelector('#more-comment-box div');
if (moreCommentButton) {
  moreCommentButton.addEventListener('click', function(event){
    var postId = document.querySelector('#more-comment-box input:nth-child(1)').value;
    var commentCountData = document.querySelector('#more-comment-box input:nth-child(2)');
    var data = 'postId='+postId;
    data += '&count='+commentCountData.value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/blog/process/process-more-comment.php');
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(data);
    xhr.onreadystatechange = function(){
      if(xhr.readyState === 4 && xhr.status === 200){
        // openCommonPopUp('디버그', xhr.responseText);
        var data = JSON.parse(xhr.responseText);
        var isLastComment = data.isLastComment;
        var commentList = data.commentList;

        if (isLastComment == true) {
          var moreButton = document.querySelector('#more-comment-box div');
          moreButton.parentNode.removeChild(moreButton);
        }

        for(var i=0; i<commentList.length; i++) {
          var comment = commentList[i];
          var commentName = document.createElement('p');
          commentName.setAttribute('class', 'comment-name');
          var commentMessage = document.createElement('p');
          commentMessage.setAttribute('class', 'comment-message');
          commentName.innerHTML = '<i class="icon-comment-1"></i> '+comment.guest_name;
          commentMessage.innerText = comment.message;

          document.getElementsByClassName('comment-write-box')[0].insertBefore(
            commentName, document.getElementById('form-write-comment'));
          document.getElementsByClassName('comment-write-box')[0].insertBefore(
            commentMessage, document.getElementById('form-write-comment'));

          commentCountData.value = parseInt(commentCountData.value)+1;
        }
      }
    }
  });
}

//계정 요청 버튼 (사용 안함)
// target = document.getElementById('btn-register');
// target.addEventListener('click', function(event){
//   var data = 'email='+document.getElementById('login-form-email').value;
//   data += '&password='+document.getElementById('login-form-password').value;
//
//   var xhr = new XMLHttpRequest();
//   xhr.open('POST', '/blog/process-register.php');
//   xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//   xhr.send(data);
//   xhr.onreadystatechange = function(){
//     if(xhr.readyState === 4 && xhr.status === 200){
//       // openCommonPopUp('디버그', xhr.responseText);
//       var data = JSON.parse(xhr.responseText);
//       closePopUpById('login-popup');
//       openCommonPopUp('계정등록', data.resultMessage);
//     }
//   }
// });

//본문에 파일/이미지 추가
// var writeToolBoxs = document.querySelectorAll('.write-tool-box input');
// if (writeToolBoxs) {
//   for(var i=0; i<writeToolBoxs.length; i++) {
//     writeToolBoxs[i].addEventListener('change', function(event) {
//       var table = document.querySelector('#uploaded-files');
//       var files = this.files;
//       for(var i=0; i<files.length; i++) {
//         var tr = document.createElement('tr');
//         var size = formatFileSize(files[i].size);
//         var isImage = (this.name=="images");
//
//         focusFixedSelectionRange(document.getElementsByClassName('textarea')[0]);
//
//         if (isImage) {
//           var img = new Image();
//           img.onload = function() {
//             // var sizes = {
//             //   width:this.width,
//             //   height:this.height
//             // };
//             URL.revokeObjectURL(this.src);
//             console.log(this.src);
//           }
//           img.src = URL.createObjectURL(files[i]);
//           insertImageAtCursor(img.src, );
//         } else {
//           insertTextAtCursor(files[i].name);
//         }
//
//         var icon = isImage ? '<i class="icon-file-image"></i>' : '<i class="icon-file-archive"></i>';
//         tr.innerHTML = '<td>'+icon+files[i].name+'('+size+')'+'</td>'
//           +'<td><a href="#">본문삽입</a><a href="#"><i class="icon-cancel"></i>삭제</a></td>';
//         table.appendChild(tr);
//       }
//     });
//   }
// }

//수정한 부분(cursor) 저장
// var textArea = document.querySelector('.textarea');
// if (textArea) {
//   textArea.addEventListener('click', function(event){
//     fixedSelectionRange(this);
//   });
// }

// 게시글 submit 할때 div의 내용을 POST 데이터로 같이 쏴야한다.
// var postForm = document.getElementById('post-form');
// if (postForm) {
//   postForm.addEventListener('submit', function(event){
//     event.preventDefault();
//
//     var input = document.getElementById('post-hidden-input');
//     if (input) {
//       input.value = document.querySelector('.textarea').innserText;
//     } else {
//       input = document.createElement('input');
//       input.setAttribute('id', 'post-hidden-input');
//       input.type = "hidden";
//       input.name = "content";
//       input.value = document.querySelector('.textarea').innerText;
//       this.appendChild(input);
//     }
//
//     this.submit();
//   });
// }
