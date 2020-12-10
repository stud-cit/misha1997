<script>
    export default {
        computed: {
            years() {
                const year = new Date().getFullYear();
                return Array.from({length: year - 1969}, (value, index) => 1970 + index).reverse();
            },
        }
    }
</script>