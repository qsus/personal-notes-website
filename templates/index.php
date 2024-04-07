<!DOCTYPE html>
<html>
	<head>
		<title>Notes</title>
		<meta charset="utf-8">
		<style>
			* {
				box-sizing: border-box;
			}
			textarea {
				width: 100%;
				height: 30em;
				display: block;
			}
			body {
				font-family: sans-serif;
			}
		</style>
		<script>
			function save(file) {
				switch (file) {
					case "notesTxt":
						var data = document.getElementById("notesTxtTextarea").value;
						break;
					case "notesHtml":
						var data = document.getElementById("notesHtml").value;
						break;
					default:
						alert("Unknown file: " + file);
						return;
				}
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "save.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						var json = JSON.parse(xhr.responseText);
						if (!json.success)
							alert("Error: " + json.error);
						else
							alert("Saved!");
					}
				};
				xhr.send("file=" + file + "&data=" + encodeURIComponent(data));
			}
			function upload() {
				var file = document.getElementById("file").files[0];
				var formData = new FormData();
				formData.append("file", file);
				formData.append("filename", document.getElementById("filename").value);
				formData.append("override", document.getElementById("override").checked);
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "upload.php", true);
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						var json = JSON.parse(xhr.responseText);
						if (!json.success)
							alert("Error: " + json.error);
						else
							alert("Uploaded!");
					}
				};
				xhr.send(formData);
			}
			function autoGrow(element) {
				element.style.height = "5em";
				element.style.height = (element.scrollHeight + 5) + "px";
			}
		</script>
	</head>
	<body>
		<h1>Notes</h1>
		<fieldset>
			<legend><input autocomplete="off" type="checkbox" id="showTxtCheckbox" onclick="
				document.getElementById('notesTxtWrapper').style.display = this.checked ? 'block' : 'none';
				autoGrow(document.getElementById('notesTxtTextarea'));
			"><label for="showTxtCheckbox">Display notes</label></legend>
			
			<form id="notesTxtWrapper" style="display: none;">
				<textarea id="notesTxtTextarea" autocomplete="off" oninput="autoGrow(this);"><?php echo htmlspecialchars(file_get_contents(__DIR__.'/../uploads/notes.txt')); ?></textarea>
				<button id="saveBtn" onclick="save('notesTxt'); return false;">Save</button>
			</form>
		</fieldset>
		<fieldset>
			<legend><input autocomplete="off" type="checkbox" id="showHtmlCheckbox" onclick="
				document.getElementById('notesHtmlWrapper').style.display = this.checked ? 'block' : 'none';
				if (this.checked) {
					var iframe = document.getElementById('notesHtmlContent');
					iframe.style.height = (iframe.contentWindow.document.documentElement.scrollHeight + 5) + 'px';
				}
			"><label for="showHtmlCheckbox">Display HTML notes</label></legend>
			
			<div id="notesHtmlWrapper" style="display: none;">
				<iframe id="notesHtmlContent" src="download.php?file=notes.html" style="width: 100%; height: 20em; border: 1px dashed gray;" ></iframe>

				<!-- show editing form -->
				<fieldset>
					<legend><input autocomplete="off" type="checkbox" id="showHtmlEditCheckbox" onclick="
						document.getElementById('notesHtmlEditForm').style.display = this.checked ? 'block' : 'none';
						autoGrow(document.getElementById('notesHtml'));
					"><label for="showHtmlEditCheckbox">Edit HTML notes</label></legend>
					<form id="notesHtmlEditForm" style="display: none;">
						<textarea id="notesHtml" autocomplete="off" oninput="autoGrow(this)"><?php echo htmlspecialchars(file_get_contents(__DIR__.'/../uploads/notes.html')); ?></textarea>
						<button id="saveHtmlBtn" onclick="save('notesHtml'); return false;">Save</button>
					</form>
				</fieldset>
			</div>
		</fieldset>
		<fieldset>
			<legend><input autocomplete="off" type="checkbox" id="showFilesCheckbox" onclick="
				document.getElementById('filesWrapper').style.display = this.checked ? 'block' : 'none';
			"><label for="showFilesCheckbox">Display files</label></legend>
			<div id="filesWrapper" style="display: none;">
				<p>
					<?php foreach ($uploads as $file): ?>
						<!-- TODO: fix downloading file -->
						<a href="uploads/<?= $file ?>">
							<?= $file ?>
						</a>
						<sup>
							<a href="uploads/<?= $file ?>" download="">Download</a>
						</sup>
						<br>
					<?php endforeach ?>
				</p>
				
				<h2>Upload file</h2>
				<form>
					<input id="file"     type="file">
					<input id="filename" placeholder="Name">
					<input id="override" type="checkbox"><label for="override">Force override</label>
					<button onclick="upload(); return false;">Save</button>
				</form>
			</div>
		</fieldset>
		<a href="logout.php">Log out</a>
	</body>
</html>