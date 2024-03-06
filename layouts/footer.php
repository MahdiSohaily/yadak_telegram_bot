</main>
<script>
    const active = document.getElementById('active');
    const deactive = document.getElementById('deactive');

    function toggleTV() {
        const params = new URLSearchParams();
        params.append('toggle', 'toggle');
        axios
            .post("../callcenter/tvController.php", params)
            .then(function(response) {
                alert(response.data);
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    const toggleNav = () => {
        const nav = document.getElementById("nav");
        if (nav.classList.contains("open")) {
            nav.classList.remove("open");
        } else {
            nav.classList.add("open");
        }
    };
</script>
</body>

</html>