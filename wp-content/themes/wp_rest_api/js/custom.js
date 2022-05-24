var portfolioPostsBtn = document.getElementById("portfolio-posts-btn");
var portfolioPostsContainer = document.getElementById("portfolio-posts-container");

if (portfolioPostsBtn){
  portfolioPostsBtn.addEventListener("click", function(){
    var ourRequest = new XMLHttpRequest();
    ourRequest.open('GET', magicalData.siteURL + '/wp-json/wp/v2/posts?categories=2&order=asc');
    ourRequest.onload = function(){
      if(ourRequest.status >= 200 && ourRequest.status < 400){
        var data = JSON.parse(ourRequest.responseText);
        createHTML(data);
        portfolioPostsBtn.remove();
      }
      else{
        console.log("We connected to the server, but it returned an error.");
      }
    };
    ourRequest.onerror = function(){
      console.log("Connection error");
    };
    ourRequest.send();
  });
}

function createHTML(postsData) {
  var ourHTMLString = '';
  for (i = 0; i < postsData.length; i++) {
    ourHTMLString += '<h2>' + postsData[i].title.rendered + '</h2>';
    ourHTMLString += postsData[i].content.rendered;
  }
  portfolioPostsContainer.innerHTML = ourHTMLString;
}

// Quick Add Post AJAX
var quickAddButton = document.querySelector("#quick-add-button");

if (quickAddButton) {
  quickAddButton.addEventListener("click", function() {
    var ourPostData = {
      "title": document.querySelector('.admin-quick-add [name="title"]').value,
      "content": document.querySelector('.admin-quick-add [name="content"]').value,
      "status": "publish"
    }

    var userData = {
      'username': 'wp_rest_api',
      'password': 'wp_rest_api@123'
    }
    // JWT authentication
    var authRequest = new XMLHttpRequest();
    authRequest.open("POST", 'http://localhost/wp_rest_api/wp-json/jwt-auth/v1/token');
    authRequest.setRequestHeader("Content-Type", "application/json");
    authRequest.setRequestHeader("Accept", "application/json");
    authRequest.send(JSON.stringify(userData));
    authRequest.onreadystatechange = function(){
      if(authRequest.readyState == 4){
        console.log(authRequest.responseText);
        var authJson = JSON.parse(authRequest.responseText);
        var token = authJson.token;
        console.log(token);

        var createPost = new XMLHttpRequest();
        createPost.open("POST", magicalData.siteURL + "/wp-json/wp/v2/posts");
        // createPost.setRequestHeader("X-WP-Nonce", magicalData.nonce);
        createPost.setRequestHeader("Authorization", 'Bearer '+token);
        createPost.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        createPost.send(JSON.stringify(ourPostData));
        createPost.onreadystatechange = function(){
          if(createPost.readyState == 4){
            if(createPost.status == 201){
              document.querySelector('.admin-quick-add [name="title"]').value = '';
              document.querySelector('.admin-quick-add [name="content"]').value = '';
            }
            else{
              alert("Error - try again.");
            }
          }
        }
        
      }
    }


    




  });
}