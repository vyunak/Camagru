<div class="webcam d-flex flex-wrap col-sm-9">
	<div class="col-sm-12 xyuina d-flex flex-wrap">
		<div class="btn-group col-sm-6" role="group" aria-label="Basic example">
			<button type="button" id="snap" class="btn btn-success">Stop/Start Camera</button>
			<button type="button" id="preview" class="btn btn-light">Take a photo</button>
		</div>
		<form class="custom-file col-sm-6" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="token" value="<?php echo($FToken) ?>">
		<?php if (!empty($_POST['submit']) && $_POST['submit'] != 'loadPhoto' || empty($_POST['submit'])): ?>
			<input type="file" class="custom-file-input" id="userPhotoLoad" name="userPhoto">
			<label class="custom-file-label" for="userPhotoLoad"></label>
			<button type="submit" name="submit" value="loadPhoto" class="btn btn-warning">Load photo</button>
		<?php else: ?>
			<button type="submit" name="submit" value="clearPhoto" class="btn btn-primary" id="clearPhoto">Clear photo</button>
		<?php endif; ?>
		</form>
	</div>
	<div class="col-sm-6 webcam-camera">
		<video <?php echo ((!empty($videoimg)) ? "poster='data:image/png;base64,{$videoimg}'" : "") ?> style="display: none" id="video" autoplay></video>
		<canvas id="canvas" width="640" height="480">We are sorry, your browser doesn't support HTML5!</canvas>
	</div>
	<div class="col-sm-6 webcam-pult d-flex flex-wrap">
		<div class="col-sm-4 webcam-stickers">
			<ul class="webcam-sticker-list">
			</ul>
		</div>
		<div class="col-sm-4 webcam-prevphoto">
			<div class="webcam-stickers">
				<ul class="webcam-sticker-list">
					<li class="sticker"><img src="/public/images/stickers/1.png" alt="sticker-1" onclick="addEmoji(0)"></li>
					<li class="sticker"><img src="/public/images/stickers/2.png" alt="sticker-2" onclick="addEmoji(1)"></li>
					<li class="sticker"><img src="/public/images/stickers/3.png" alt="sticker-3" onclick="addEmoji(2)"></li>
					<li class="sticker"><img src="/public/images/stickers/4.png" alt="sticker-4" onclick="addEmoji(3)"></li>
					<li class="sticker"><img src="/public/images/stickers/5.png" alt="sticker-5" onclick="addEmoji(4)"></li>
					<li class="sticker"><img src="/public/images/stickers/6.png" alt="sticker-6" onclick="addEmoji(5)"></li>
					<li class="sticker"><img src="/public/images/stickers/7.png" alt="sticker-7" onclick="addEmoji(6)"></li>
					<li class="sticker"><img src="/public/images/stickers/8.png" alt="sticker-8" onclick="addEmoji(7)"></li>
					<li class="sticker"><img src="/public/images/stickers/9.png" alt="sticker-9" onclick="addEmoji(8)"></li>
					<li class="sticker"><img src="/public/images/stickers/10.png" alt="sticker-10" onclick="addEmoji(9)"></li>
					<li class="sticker"><img src="/public/images/stickers/11.png" alt="sticker-11" onclick="addEmoji(10)"></li>
					<li class="sticker"><img src="/public/images/stickers/12.png" alt="sticker-12" onclick="addEmoji(11)"></li>
					<li class="sticker"><img src="/public/images/stickers/13.png" alt="sticker-13" onclick="addEmoji(12)"></li>
					<li class="sticker"><img src="/public/images/stickers/14.png" alt="sticker-14" onclick="addEmoji(13)"></li>
					<li class="sticker"><img src="/public/images/stickers/15.png" alt="sticker-15" onclick="addEmoji(14)"></li>
					<li class="sticker"><img src="/public/images/stickers/16.png" alt="sticker-16" onclick="addEmoji(15)"></li>
					<li class="sticker"><img src="/public/images/stickers/17.png" alt="sticker-17" onclick="addEmoji(16)"></li>
					<li class="sticker"><img src="/public/images/stickers/18.png" alt="sticker-18" onclick="addEmoji(17)"></li>
					<li class="sticker"><img src="/public/images/stickers/19.png" alt="sticker-19" onclick="addEmoji(18)"></li>
					<li class="sticker"><img src="/public/images/stickers/20.png" alt="sticker-20" onclick="addEmoji(19)"></li>
					<li class="sticker"><img src="/public/images/stickers/21.png" alt="sticker-21" onclick="addEmoji(20)"></li>
					<li class="sticker"><img src="/public/images/stickers/22.png" alt="sticker-22" onclick="addEmoji(21)"></li>
					<li class="sticker"><img src="/public/images/stickers/23.png" alt="sticker-23" onclick="addEmoji(22)"></li>
					<li class="sticker"><img src="/public/images/stickers/24.png" alt="sticker-24" onclick="addEmoji(23)"></li>
					<li class="sticker"><img src="/public/images/stickers/25.png" alt="sticker-25" onclick="addEmoji(24)"></li>
					<li class="sticker"><img src="/public/images/stickers/26.png" alt="sticker-26" onclick="addEmoji(25)"></li>
					<!-- (li.sticker>img[src="/public/images/stickers/$.png" alt="sticker-$" onclick="addEmoji($@0)"]) -->
				</ul>
			</div>
		</div>
		<div class="col-sm-4 list-photo-img">
			<form action="/post/new" method="POST" id="form" enctype="multipart/form-data">
				<div class="list-img">
				</div>
				<input type="hidden" name="token" value="<?php echo($FToken) ?>">
				<li><button class="btn btn-primary" id="goToPublic" disabled="">Publish photo</button></li>
			</form>
		</div>
	</div>
</div>

<script>
	var video = document.getElementById('video');
	var videPoster = video.getAttribute('poster');
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');
	var header = document.getElementsByClassName("header")[0].offsetHeight;

	document.addEventListener("DOMContentLoaded", function() {
		if (document.getElementsByClassName("custom-file-label") != undefined)
		{
			document.getElementsByClassName("custom-file-label")[0].innerHTML = "No file chosen";
			document.getElementsByClassName("custom-file-input")[0].onchange = function() {
				document.getElementsByClassName("custom-file-label")[0].innerHTML = this.files[0].name;
			}
		}
	});

	var C_WIDTH = canvas.offsetWidth;
	var C_HEIGHT = canvas.offsetHeight;

	var images = [
	{'startX': (C_WIDTH / 2) - 100, 'startY': (C_HEIGHT / 2) - 100, 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '1'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 100, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '2'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 100, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '3'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 100, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '4'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '5'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '6'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 175, 'height': 100, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '7'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 300, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '8'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 300, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '9'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 300, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '10'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 75, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '11'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 100, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '12'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 150, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '13'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '14'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '15'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '16'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '17'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '18'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '19'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '20'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 100, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '21'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 100, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '22'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '23'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '24'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 150, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '25'},
	{'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': 200, 'height': 200, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': '26'},
	];

	function addEmoji(num) {
		var index = images.length;
		var newLi = document.createElement("li");
		newLi.className = "sticker";
		newLi.id = `sticker-${index}`;
		newLi.innerHTML = `<img onmouseout="borderEmoji(${index}, 0)" onmouseenter="borderEmoji(${index}, 1)" onclick="selectEmoji(${index});" src="/public/images/stickers/${images[num].urlImg}.png" alt="sticker-${images[num].urlImg}"><div onclick="deleteEmoji(${index});">Delete</div>`;
		document.getElementsByClassName("webcam-sticker-list")[0].appendChild(newLi)
		images.push({'startX': (C_WIDTH / 2), 'startY': (C_HEIGHT / 2), 'width': images[num].width, 'height': images[num].height, 'img': new Image(), 'move': 0, 'border': 0, 'urlImg': images[num].urlImg});
		images[index].img.src = "public/images/stickers/"+(images[num].urlImg)+".png";
		selectEmoji(index);
	}

	function deleteEmoji(num, button) {
		delete images[num];
		document.getElementById(`sticker-${num}`).remove();
	}

	context.translate(canvas.width, 0);
	context.scale(-1, 1);
	var screen = 0;

	/**/
	function selectEmoji(num)
	{
		images.forEach(elem => {
			if (elem.move == 1)
				elem.move = 0;
		})

		images[num].img.src = "public/images/stickers/"+(images[num].urlImg)+".png";
		images[num].move = 1;
	}

	function borderEmoji(num, bde)
	{
		images.forEach(elem => {
			if (elem.border == 1)
				elem.border = 0;
		})
		if (bde == 1)
			images[num].border = 1;
	}

	/**/
	function update_screen(mouseFunc = undefined)
	{
		if (mouseFunc != undefined && mouseFunc.type == 'mousewheel')
		{
			images.forEach(function(f1) {
				if (f1.move == 1)
				{
					if (((mouseFunc.deltaY > 0) ? f1.width / 1.05 : f1.width * 1.05) < C_WIDTH &&
						((mouseFunc.deltaY > 0) ? f1.width / 1.05 : f1.width * 1.05) > 25 &&
						((mouseFunc.deltaY > 0) ? f1.height / 1.05 : f1.height * 1.05) < C_HEIGHT)
					{
						f1.width = (mouseFunc.deltaY > 0) ? f1.width / 1.05 : f1.width * 1.05;
						f1.height = (mouseFunc.deltaY > 0) ? f1.height / 1.05 : f1.height * 1.05;
					}
				}
			})
		}
		else if (mouseFunc != undefined && mouseFunc.type == 'mousemove')
		{
			images.forEach(function(f1) {
				if (f1.move == 1)
				{
					f1.startX = (mouseFunc.pageX - (canvas.offsetParent.offsetParent.offsetLeft + canvas.offsetParent.offsetLeft + canvas.offsetLeft));
					f1.startY = ((mouseFunc.pageY - header) - (canvas.offsetParent.offsetParent.offsetTop + canvas.offsetParent.offsetTop + canvas.offsetTop));
				}
			})
		}
		else if (mouseFunc != undefined && mouseFunc.type == 'click')
		{
			images.forEach(function(f1) {
				if (f1.move == 1)
					f1.move = 0;
			})
		}
		context.clearRect(0,0, C_WIDTH, C_HEIGHT);
		context.drawImage(video, 0, 0, C_WIDTH, C_HEIGHT);
		images.forEach(function(f1) {
			context.drawImage(f1.img, -f1.startX + (-(f1.width / 2) + C_WIDTH), f1.startY - (f1.height / 2), f1.width, f1.height);
			if (f1.border == 1)
			{
				context.strokeStyle = '#fff';
				context.lineWidth = 2;
				context.strokeRect(-f1.startX + (-(f1.width / 2) + C_WIDTH), f1.startY - (f1.height / 2), f1.width, f1.height);
			}
		})
	}

	/* ------------------------------- */
	canvas.onclick = function(evt) {
		update_screen(evt);
	}

	canvas.onmousewheel = function (evt) {
		update_screen(evt);
	}

	canvas.onmousemove = function(evt) {
		update_screen(evt);
	}
	/* \-----------------------------/ */

	/**/

	setInterval(function(){
		if (screen == 0)
			update_screen();
	})

	document.getElementById("preview").addEventListener("click", function() {
		var lenCount = document.getElementsByClassName("n-img").length;
		var rendnum = Math.floor(Math.random() * Math.floor(1e5));
		if (lenCount > 2) {
			document.getElementsByClassName("n-img")[0].remove();
			document.getElementsByClassName("n-img")[0].children[0].checked = "checked";
		}
		var dataUrl = canvas.toDataURL();
		var newNode = document.createElement('li');
		var newNodeInput = document.createElement('input');
		newNode.className = 'n-img';
		newNodeInput.type = 'radio';
		if (lenCount == 0) {
			newNodeInput.checked = "checked";
			document.getElementById('goToPublic').disabled = false;
		}
		newNodeInput.name = 'userPhoto';
		newNodeInput.value = dataUrl;
		newNodeInput.id = 'photo'+rendnum;
		var newNodelabel = document.createElement('label');
		newNodelabel.htmlFor = 'photo'+rendnum;
		var newNodeImg = document.createElement('img');
		newNodeImg.src = dataUrl;
		newNodeImg.className = 'list-photo w-100';
		newNodelabel.appendChild(newNodeImg);
		newNode.appendChild(newNodeInput);
		newNode.appendChild(newNodelabel);
		document.getElementsByClassName('list-img')[0].appendChild(newNode);
	});

	document.getElementById("goToPublic").addEventListener("click", function() {
		img = document.getElementById("userPhoto").value;
		if (img.length > 500)
		{
			var form = document.getElementById("form");
			document.getElementById("userPhoto").value = img;
			console.log(form.submit());
		}
	});

	if (videPoster == null)
	{

		if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
			navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
				video.srcObject = stream;
				video.play();
			});
		}

		document.getElementById("snap").addEventListener("click", function() {
			if (video.paused == false)
			{
				video.pause();
				screen = video;
			}
			else {
				video.play();
				screen = 0;
			}
			update_screen();
		});

	}
	else
	{
		video = new Image();
		video.src = videPoster;
	}
</script>