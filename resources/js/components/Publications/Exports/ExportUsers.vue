<template>
    <div>
        <button class="export-button" @click="getData()" :disabled="loading">
            <span
                class="spinner-border spinner-border-sm"
                style="width: 19px; height: 19px; margin-right: 10px"
                role="status"
                aria-hidden="true"
                v-if="loading"
            ></span>
            <img v-else src="/img/download.png"> Експорт списку користувачів
        </button>
        <table id="exportUsers" v-show="false">
            <tr>
                <th>ID</th>
                <th>ПІБ</th>
                <th>Вік</th>
                <th>Посада</th>
                <th>Академічна група</th>
                <th>Факультет/інститут</th>
                <th>Кафедра</th>
                <th>Країна</th>
                <th>Індекс Гірша БД WoS</th>
                <th>Індекс Гірша БД Scopus</th>
                <th>Research ID</th>
                <th>ORCID</th>
                <th>5 або більше публікацій у періодичних виданнях в Scopus та/або WoS</th>
            </tr>
            <tr v-for="(item, i) in data" :key="i">
                <td>{{i+1}}</td>
                <td>{{item.name}}</td>
                <td>{{item.age}}</td>
                <td>{{item.position}}</td>
                <td>'{{item.academic_code}}'</td>
                <td>{{item.faculty}}</td>
                <td>{{item.department}}</td>
                <td>{{item.country}}</td>
                <td>{{item.h_index}}</td>
                <td>{{item.scopus_autor_id}}</td>
                <td>{{item.scopus_researcher_id}}</td>
                <td>{{item.orcid}}</td>
                <td>{{item.five_publications ? "Так" : "Ні"}}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    import XLSX from 'xlsx';
    export default {
        data() {
            return {
                loading: false,
                data: []
            }
        },
        props: {
            filters: Object,
            countUsers: Number
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get('/api/authors', {
                    params: {
                        size: this.countUsers,
                        name: this.filters.name,
                        faculty_code: this.filters.faculty_code,
                        department_code: this.filters.department_code,
                        h_index: this.filters.h_index,
                        categ_users: this.filters.categ_users,
                        role: this.filters.role
                    }
                }).then(response => {
                    this.data = response.data.users.data;
                    this.loading = false;
                }).then(() => {
                    this.exportUsers();
                }).catch(() => {
                    this.loading = false;
                })

            },
            exportUsers() {
                const authors = XLSX.utils.table_to_book(document.getElementById('exportUsers'));
                const wb = XLSX.utils.book_new();
                wb.SheetNames.push("Authors");
                wb.Sheets.Authors = authors.Sheets.Sheet1;
                wb.Sheets.Authors['!cols'] = [
                    { wch: 5 },  // id
                    { wch: 30 }, // ПІБ
                    { wch: 5 },  // Вік
                    { wch: 10 }, // Посада
                    { wch: 10 }, // Академічна група
                    { wch: 10 }, // Факультет/інститут
                    { wch: 10 }, // Кафедра
                    { wch: 10 }, // Країна
                    { wch: 5 }, // Індекс Гірша БД WoS
                    { wch: 5 }, // Індекс Гірша БД Scopus
                    { wch: 5 }, // Research ID
                    { wch: 5 }, // ORCID
                    { wch: 5 }  // 5 або більше публікацій в Scopus та/або WoS
                ];
                XLSX.writeFile(wb, 'Authors.xlsx');
            },
        }
    }
</script>
