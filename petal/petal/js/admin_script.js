let navbar = document.querySelector(".header .flex .navbar");
let userBox = document.querySelector(".header .flex .account-box");

document.querySelector("#menu-btn").onclick = () => {
  navbar.classList.toggle("active");
  userBox.classList.remove("active");
};

document.querySelector("#user-btn").onclick = () => {
  userBox.classList.toggle("active");
  navbar.classList.remove("active");
};

window.onscroll = () => {
  navbar.classList.remove("active");
  userBox.classList.remove("active");
};
let flag = 0;
function deactivate() {
  if (flag == 0) {
    //  friends.innerHTML = "Friends";
    //  friends.style.color = "green";
    //  btn.textContent = "Remove Friend";
    alert(working);
    flag = 1;
  } else {
    friends.innerHTML = "Stranger";
    friends.style.color = "red";
    btn.textContent = "Add Friend";
    flag = 0;
  }
}
