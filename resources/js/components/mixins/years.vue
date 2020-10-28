<script>
    export default {
        computed: {
            years() {
                const year = new Date().getFullYear();
                return Array.from({length: year - 2000}, (value, index) => 2001 + index).reverse();
            },
        }
    }
</script>