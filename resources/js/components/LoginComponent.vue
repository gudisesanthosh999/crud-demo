<template>
  <div class="heading">
    <h1>Login Page</h1>
  </div>
    <br>
  <div class="container mt-5" style="max-width:400px">
    <br><br>
    <br>
    <h3 class="mb-3 text-center">Login</h3>
    

    <div class="mb-3">
      <label>Email:</label>
      <input v-model="email" class="form-control" placeholder="Email">
    </div>

    <div class="mb-3">
      <label>Password:</label>
      <input v-model="password" type="password" class="form-control" placeholder="Password">
    </div>

    <button class="btn btn-primary w-100" @click="login">
      Login
    </button>

    <div v-if="error" class="text-danger mt-2">
      {{ error }}
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: '',
      error: null
    }
  },

  methods: {
    async login() {
      try {
        const res = await axios.post('/api/login', {
          email: this.email,
          password: this.password
        });

        localStorage.setItem('token', res.data.token);
        axios.defaults.headers.common['Authorization'] =
          `Bearer ${res.data.token}`;

        window.location.href = '/items';
      } catch (e) {
        this.error = 'Invalid credentials';
      }
    }
  }
}
</script>

<style scoped>
.container{
    background-color: rgb(127, 150, 250);
    height: 500px;
    border-radius: 30px;
    padding: 50px;
}
.heading {
  position: relative;
  height: 80px;
  background: #1e293b;
  color: #ffff;
  display: flex;
  align-items: center;
  justify-content: center;
  
}
</style>
