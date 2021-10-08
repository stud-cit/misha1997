export default {
  data() {
    return {
      departments: [],
      divisions: [],
      loadDivisions: false
    }
  },
  methods: {
    getDivisions() {
      if(this.divisions.length == 0) {
        this.loadDivisions = true;
        axios.get('/api/sort-divisions').then(response => {
          this.divisions = response.data;
          this.loadDivisions = false;
        }).catch(() => {
          this.loadDivisions = false;
        })
      }
    },
    getDepartments() {
      if(this.filter.faculty_code) {
        this.departments = [].concat(...this.divisions.filter(item => {
          return this.filter.faculty_code.indexOf(item.ID_DIV) >= 0
        }).map(item => { 
          return item.departments;
        }));
      } else {
        this.departments = [];
        this.filter.department_code = "";
      }
    }
  }
}