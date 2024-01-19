window.onload = () => {
  document.getElementById('buscar_bajas').addEventListener('click', (e) => {
    e.preventDefault();
    const reload = $.fn.dataTable.isDataTable('#tabla_bajas');
    tablaBajas(e, reload);
  });
};

const tablaBajas = async (event, recargar = false) => {
  event.preventDefault();
  const desde = $('#desde').val();
  const hasta = $('#hasta').val();

  if (desde == '' || hasta == '') {
    error('Debe completa "Desde" y "Hasta" para buscar por Fecha');
    return false;
  } else if (desde > hasta) {
    warning('"Desde" debe ser menor o igual que "hasta"');
    return false;
  }

  const datos = function (data) {
    data.desde = $('#desde').val();
    data.hasta = $('#hasta').val();
  };

  const columns = [
    'id',
    'nombre_socio',
    'nombre_funcionario',
    'cedula_socio',
    'sucursal',
    'motivo_baja',
    'motivo_no_otorgada',
    'estado',
    'fecha_ingreso_baja',
    'fecha_fin_gestion',
    'area_fin_gestion',
    'observaciones',
    'observacion_final',
  ];

  showLoading();
  await tabla('tabla_bajas', 'PHP/AJAX/bajas.php', datos, columns, recargar);
  hideLoading();
};

const limparBusqueda = () => {
  $('#desde').val('');
  $('#hasta').val('');
};
