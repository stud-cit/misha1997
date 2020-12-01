<template>
    <div>
        <button @click="close" class="prev">Відмінити</button>
    </div>
</template>
<script>
export default {
    methods: {
        close() {
            swal.fire({
                title: "Внесені зміни не будуть збережені",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Згоден',
                cancelButtonText: 'Закрити',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$router.go(-1);
                }
            })
        }
    },
}
</script>