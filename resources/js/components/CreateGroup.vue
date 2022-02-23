<template>
  <div class="card">
    <div class="card-header">Create Group</div>
    <div class="card-body">
      <form>
        <div class="form-group">
          <input type="text" class="form-control" v-model="name" placeholder="Group name">
        </div>
        <div class="form-group" style="margin-top: 10px">
          <select v-model="users" multiple id="friends" style="width: 100%">
            <option v-for="user in initialUsers" :value="user.id">
              {{ user.name }}
            </option>
          </select>
        </div>
      </form>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-primary" @click.prevent="createGroup">Create Group</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ['initialUsers'],
  data() {
    return {
      name: '',
      users: []
    }
  },
  methods: {
    createGroup() {
      axios.post('/groups', {name: this.name, users: this.users})
          .then((response) => {
            this.name = '';
            this.users = [];
            Bus.$emit('groupCreated', response.data);
          });
    }
  }
}
</script>
