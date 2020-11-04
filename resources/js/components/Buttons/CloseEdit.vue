<template>
    <div>
        <button @click="close" class="prev">Відміна</button>
    </div>
</template>
<script>
export default {
    methods: {
        close() {
            swal({
                text: "Внесені зміни не будуть збережені",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    this.$router.go(-1);
                }
            });
        }
    },
}
</script>