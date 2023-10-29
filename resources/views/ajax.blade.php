<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test ajax</title>
</head>
<body>
    <form action="post">
        <input type="number" name="id" id="id">
        <button class="submit">Кинуть AJAX запрос</button>
    </form>
    <div class="output">

    </div>
</body>
<script>
    const button = document.querySelector(".submit");

    button.addEventListener("click", async (event) => {
        event.preventDefault();
        const id = document.querySelector('#id').value;
        const response = await fetch(`${window.location.origin}/api/conf/${id}`);
        
        if (response.ok) {
            let unpack = await response.json();
            console.log(unpack.data);
            const output = document.querySelector(".output");
            
            output.innerHTML = unpack.data.toString();
        }
    })

</script>
</html>