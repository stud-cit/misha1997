<template>
  <div>
    <b-form-group label="Роль:" class="mb-1">
      <b-form-select
        value-field="id"
        text-field="name"
        :options="roles" 
        size="sm"
        v-model="data.roles_id"
        class="mb-3"
      ></b-form-select>
    </b-form-group>

    <b-form-group label="Інститут/факультет:" class="mb-1">
      <b-form-select
        value-field="ID_DIV"
        text-field="NAME_DIV"
        :options="divisions" 
        size="sm"
        v-model="data.faculty_code"
        class="mb-3"
      ></b-form-select>
    </b-form-group>

    <b-form-group label="Кафедра:" class="mb-1">
      <b-form-select
        value-field="ID_DIV"
        text-field="NAME_DIV"
        :options="departmentsUser" 
        size="sm"
        v-model="data.department_code"
        class="mb-3"
      ></b-form-select>
    </b-form-group>

    <b-form-group label="Доступ до сервісу:" class="mb-1">
      <b-form-select
        value-field="id"
        text-field="name"
        :options="[{id: 'open', name: 'Повний'}, {id: 'close', name: 'Обмежений'}]" 
        size="sm"
        v-model="data.access"
        class="mb-3"
      ></b-form-select>
    </b-form-group>

    <b-button @click="save">Примінити</b-button>
  </div>
</template>
<script>
import roles from '../mixins/roles.js';
import divisions from '../mixins/divisions.js';

export default {
  mixins: [roles, divisions],
  data() {
    return {
      data: {
        roles_id: null,
        faculty_code: null,
        department_code: null,
        access: ""
      }
    }
  },
  created() {
    this.getUser();
    this.getAccess();
    this.getDivisions();
  },
  computed: {
    departmentsUser() {
      let departments = [];
      if(this.data.faculty_code) {
          departments = this.divisions.find(item => {
              return this.data.faculty_code == item.ID_DIV
          });
          if(departments) {
              departments = departments.departments;
          }
      } else {
          departments = [];
          this.data.department_code = "";
      }
      return departments;
    }
  },
  methods: {
    getUser() {
      axios.get('/api/user/2')
      .then(response => {
        this.data = Object.assign(this.data, response.data);
      })
    },
    getAccess() {
      axios.get('/api/access')
      .then(response => {
        this.data.access = response.data.value;
      })
    },
    save() {
      axios.post('/api/dev', this.data)
      .then(() => {
          window.location.reload();
      });
    }
  }
}
</script>