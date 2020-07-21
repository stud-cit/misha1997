<template>
    <div>



        <button @click="exportPublications('export')">export publications</button>
        <div id="export">
            <h2>	Статті у фахових виданнях України</h2>
            <ol>
                <li>
                    Прізвище, ініціали,  назва статті / ініціали, прізвищ(а)е співавтор(ів)а // назва журналу. – рік видання. – Т.  , №  . – С. номера сторінок з – по.

                </li>
                <li>
                    Прізвище, ініціали,  назва статті / ініціали, прізвищ(а)е співавтор(ів)а // назва журналу. – рік видання. – Т.  , №  . – С. номера сторінок з – по.

                </li>
            </ol>
        </div>

    </div>
</template>

<script>

    export default {
        data() {
            return {


            };
        },
        components: {

        },

        methods: {

            exportPublications(id){
                var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
                    "xmlns:w='urn:schemas-microsoft-com:office:word' "+
                    "xmlns='http://www.w3.org/TR/REC-html40'>"+
                    "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title><style></style></head><body>";
                var footer = "</body></html>";
                var sourceHTML = header+document.getElementById(id).innerHTML+footer;

                var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
                var fileDownload = document.createElement("a");
                document.body.appendChild(fileDownload);
                fileDownload.href = source;
                fileDownload.download = 'document.doc';
                fileDownload.click();
                document.body.removeChild(fileDownload);
            }


        },

    }
</script>

<style lang="scss" scoped>


</style>
