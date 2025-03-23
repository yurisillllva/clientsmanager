<template>
  <div class="dashboard container">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <header>
        <div class="row mb-3 my-1 mr-2 g-2">
          <div class="col-9">
            <nav>
              <button @click="logout">
                <i class="bi bi-box-arrow-left"></i>
              </button>
            </nav>
          </div>
          <div class="col-3">
            <h3 class="mb-0 me-5">Contatos</h3>
          </div>
        </div>
      </header>
    </div>

    <ClientList />
    <router-view />
  </div>
</template>

<script>
import ClientList from "@/components/ClientList.vue";

export default {
  components: { ClientList },
  methods: {
    showCharts() {
      this.$router.push("/dashboard/charts");
    },
    logout() {
      localStorage.removeItem("token");
      this.$router.push("/login");
    },
  },
};
</script>