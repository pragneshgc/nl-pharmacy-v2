import { defineStore } from "pinia";

export const useDefaultStore = defineStore('default', {
  state: () => ({
    checked: [],
    tableData: [],
    visible: [],
    tray: [],
    printLog: localStorage.getItem("printlog")
      ? JSON.parse(localStorage.getItem("printlog"))
      : [],
    trayUser: 0,
    style: localStorage.getItem("interfacestyle")
      ? localStorage.getItem("interfacestyle")
      : "default-style",
    isOnline: navigator.onLine,
  }),
  getters: {
    isDemo: (state) => {
      return appInfo.mode == 'local' || appInfo.mode == 'staging' || appInfo.mode == 'demo'
    },
    addChecked: (state, id) => {
      if (state.checked.indexOf(id) == -1) {
        state.checked.push(id);
      }
    },
    changeStyle: (state, style) => {
      state.style = style;
    },
    removeChecked: (state, id) => {
      var index = state.checked.indexOf(id);
      if (index !== -1) state.checked.splice(index, 1);
    },
    toggleChecked: (state, id) => {
      let index = state.checked.indexOf(id);
      if (index !== -1) {
        state.checked.splice(index, 1);
      } else {
        state.checked.push(id);
      }
    },
    replaceChecked: (state, ids) => {
      state.checked = ids;
    },
    replaceVisible: (state, ids) => {
      state.visible = ids;
    },
    addTableData: (state, data) => {
      state.tableData = data;
    },
    addTray: (state, order) => {
      state.tray.push(order);
    },
    removeTray: (state, id) => {
      let removeIndex = state.tray
        .map(function (item) {
          return item.TrayID;
        })
        .indexOf(parseInt(id));

      ~removeIndex && state.tray.splice(removeIndex, 1);
    },
    removeTrayPrescription: (state, id) => {
      let removeIndex = state.tray
        .map(function (item) {
          return item.PrescriptionID;
        })
        .indexOf(parseInt(id));

      ~removeIndex && state.tray.splice(removeIndex, 1);
    },
    refreshTray: (state, orders) => {
      state.tray = orders;
    },
    clearTray: (state) => {
      state.tray = [];
    },
    addLog: (state, order) => {
      state.printLog.unshift(order);
      localStorage.setItem("printlog", JSON.stringify(state.printLog));
    },
    clearLogs: (state) => {
      state.printLog = [];
      localStorage.removeItem("printlog");
    },
    checked: (state) => {
      return state.checked;
    },
    visible: (state) => {
      return state.visible;
    }
  },
  actions: {
    updateOnlineStatus(e) {
      const { type } = e;
      this.isOnline = type === 'online'
    }
  },
});
