$(function () {
//  let grid = new w2grid({
  let grid = $('#seriviceReferenceGrid').w2grid({
    name: 'seriviceReferenceGrid',
    box: '#seriviceReferenceGrid',
    header: 'List of Service Reference IDs',
    reorderRows: false,
    show: {
      header: true,
      footer: true,
      toolbar: true,
      lineNumbers: false,
    },
    columns: [
//      { field: 'csr_idx', text: 'ID', size: '30px' },
      { field: 'csr_custId',           text: 'Account', size: '30%' },
      { field: 'csr_ServiceReference', text: 'Service Reference ID', size: '30%' }
    ],
    searches: [
//      { type: 'int',  field: 'csr_idx', label: 'ID' },
      { type: 'text', field: 'csr_custId',           label: 'Account' },
      { type: 'text', field: 'csr_ServiceReference', label: 'Service Reference ID' }
    ],
  })
  grid.load('index.php?action=plant.plant_xhr.getServiceReferenceList')
});