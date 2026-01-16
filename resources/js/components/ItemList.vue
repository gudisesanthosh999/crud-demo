<template>

  <div class="heading">
    <h1>Records</h1>
    <button class=" Logoutbtn btn btn-danger mb-3" @click="logout">Logout</button>
  </div>


  <div class="main-wrapper">


    <aside class="status-panel">
      <div class="status-tab" :class="{ active: filter === '' }" @click="setStatus('')">
        All
      </div>

      <div class="status-tab" :class="{ active: filter === 'active' }" @click="setStatus('active')" >
        Active
      </div>

      <div class="status-tab" :class="{ active: filter === 'inactive' }" @click="setStatus('inactive')" >
        Inactive
      </div>
    </aside>


    <section class="records-section position-relative">


      <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success" @click="create">
          <i class="bi bi-plus-circle"></i> Create Record
        </button>
      </div>


      <table class="table table-bordered">
        <thead class="text-center">
          <tr>
            <th>UUID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Status</th>
            <th>Description</th>
            <th colspan="2">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="item in items" :key="item.uuid">
            <td>{{ item.uuid }}</td>
            <td>{{ item.name }}</td>
            <td>{{ item.code }}</td>
            <td>{{ item.status }}</td>
            <td>{{ item.description }}</td>

            <td class="action-cell">
              <button class="btn btn-sm btn-primary" @click="edit(item)">
                <i class="bi bi-pencil-square"></i> Edit
              </button>
            </td>

            <td class="action-cell">
              <button class="btn btn-sm btn-danger" @click="confirmDelete(item)">
                <i class="bi bi-trash"></i> Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>


      <div v-if="showForm" class="floating-form card shadow" :style="{ top: position.y + 'px', left: position.x + 'px' }" >
        <div class="card-header d-flex justify-content-between cursor-move" @mousedown="startDrag" >
        <strong>
          <i class="bi" :class="{
            'bi-plus-circle text-success': mode === 'create',
            'bi-pencil-square text-primary': mode === 'edit',
            'bi-trash text-danger': mode === 'delete'
          }"></i>
          {{
            mode === 'create'
              ? 'Create Record'
              : mode === 'edit'
                ? 'Edit Record'
                : 'Delete Record'
          }}
        </strong>
          
          <button class="btn-close" @click="close"></button>
        </div>

        <div class="card-body">
          <div v-if="mode === 'delete'" class="alert alert-danger">
            Are you sure you want to delete this item?
          </div>
          <label>Name: </label>
          <input v-model="form.name" class="form-control mb-2" :disabled="mode === 'delete'" />
          <label>Code: </label>
          <input v-model="form.code" class="form-control mb-2" :disabled="mode === 'delete'" />
          <label>Status: </label>
          <select v-model="form.status" class="form-select mb-2" :disabled="mode === 'delete'">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <label>Description: </label>
          <textarea v-model="form.description" class="form-control mb-3" :disabled="mode === 'delete'"></textarea>

          <div class="d-flex justify-content-between">
            <button class="btn btn-secondary" @click="close">Cancel</button>
            <button v-if="mode !== 'delete'" class="btn btn-primary" @click="save">Save</button>
            <button v-if="mode === 'delete'" class="btn btn-danger" @click="deleteItem">Delete</button>
          </div>
        </div>
      </div>

    </section>
  </div>
</template>

<script>

  import axios from 'axios';


export default {
  data() {
    return {
      items: [],
      filter: '',
      showForm: false,
      mode: 'create',
      form: {
        uuid: null,
        name: '',
        code: '',
        status: 'active',
        description: ''
      },
      position: { x: 400, y: 120 },
      dragging: false,
      offset: { x: 0, y: 0 }
    }
  },

  computed: {
    modeTitle() {
      return this.mode === 'create'
        ? 'Create Record'
        : this.mode === 'edit'
        ? 'Edit Record'
        : 'Delete Record'
    }
  },

  mounted() {
    this.fetchItems()
    window.addEventListener('mousemove', this.onDrag)
    window.addEventListener('mouseup', this.stopDrag)
  },

  methods: {

    async logout() {
      await axios.post('/api/logout');
      localStorage.removeItem('token');
      window.location.href = '/login';
    },

    setStatus(status) {
      this.filter = status
      this.fetchItems()
    },

    fetchItems() {
      axios
        .get('/api/items', { params: { status: this.filter } })
        .then(res => (this.items = res.data))
    },

    create() {
      this.resetForm()
      this.mode = 'create'
      this.showForm = true
    },

    edit(item) {
      this.form = { ...item }
      this.mode = 'edit'
      this.showForm = true
    },

    confirmDelete(item) {
      this.form = { ...item }
      this.mode = 'delete'
      this.showForm = true
    },

    close() {
      this.showForm = false
      this.resetForm()
    },

    save() {
      const req = this.form.uuid
        ? axios.put(`/api/items/${this.form.uuid}`, this.form)
        : axios.post('/api/items', this.form)

      req.then(() => {
        this.fetchItems()
        this.close()
      })
    },

    deleteItem() {
      axios.delete(`/api/items/${this.form.uuid}`).then(() => {
        this.fetchItems()
        this.close()
      })
    },

    resetForm() {
      this.form = {
        uuid: null,
        name: '',
        code: '',
        status: 'active',
        description: ''
      }
    },

    startDrag(e) {
      this.dragging = true
      this.offset.x = e.clientX - this.position.x
      this.offset.y = e.clientY - this.position.y
    },

    onDrag(e) {
      if (!this.dragging) return
      this.position.x = e.clientX - this.offset.x
      this.position.y = e.clientY - this.offset.y
    },

    stopDrag() {
      this.dragging = false
    }
  }
}
</script>

<style scoped>

.heading {
  position: relative;
  height: 80px;
  background: #1e293b;
  color: #ffff;
  display: flex;
  align-items: center;
  justify-content: center;
  
}
.Logoutbtn {
  position: absolute;
  right: 20px;  
}


.main-wrapper {
  display: flex;
  min-height: calc(100vh - 80px);
}


.status-panel {
  width: 150px;
  background-color: #1e293b;
  padding: 15px;
}


.status-tab {
  background-color: #334155;
  color: white;
  padding: 12px;
  margin-bottom: 12px;
  text-align: center;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

.status-tab.active {
  background-color: #2563eb;
}


.records-section {
  flex: 1;
  padding: 20px;
}


.action-cell {
  width: 120px;
  text-align: center;
}


.floating-form {
  position: absolute;
  width: 500px;
  z-index: 1000;
  border-radius: 20px;
}

.cursor-move {
  cursor: move;
}

.btn-primary,.btn-danger,.btn-secondary{
  border-radius: 20px;
  width: 80px;
  height: 40px;
}
.btn-success{
  border-radius: 20px;
  width: 150px;
  height: 40px;
}

table{
  border-radius: 10px;
  border: 2px solid ;
  background-color: transparent;
  
}

th,td {
  border: 1 solid;
  background-color: transparent;
}
.card-header{
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  background-color: rgb(4, 190, 128);
}
.card-body{
  border-bottom-left-radius: 20px;
  border-bottom-right-radius: 20px;
  background-color: rgb(181, 247, 225);
}

</style>
