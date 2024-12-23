function filterModels() {
  const brandSelect = document.getElementById('car-brand');
  const modelSelect = document.getElementById('car-model');
  const selectedBrand = brandSelect.value;

  // Clear existing options in the model dropdown
  modelSelect.options.length = 1;

  // Populate model options based on the selected brand
  switch (selectedBrand) {
    case 'BMW':
      addModelOption(modelSelect, 'BMW 3 Series');
      addModelOption(modelSelect, 'BMW 5 Series');
      addModelOption(modelSelect, 'BMW X5');
      break;
    case 'Honda':
      addModelOption(modelSelect, 'Honda Civic');
      addModelOption(modelSelect, 'Honda Accord');
      addModelOption(modelSelect, 'Honda CR-V');
      break;
    case 'Perodua':
      addModelOption(modelSelect, 'Perodua Myvi');
      addModelOption(modelSelect, 'Perodua Axia');
      addModelOption(modelSelect, 'Perodua Bezza');
      break;
    case 'Toyota':
      addModelOption(modelSelect, 'Toyota Corolla');
      addModelOption(modelSelect, 'Toyota Camry');
      addModelOption(modelSelect, 'Toyota Vios');
      break;
    case 'Porsche':
      addModelOption(modelSelect, 'Porsche 911');
      addModelOption(modelSelect, 'Porsche Cayenne');
      addModelOption(modelSelect, 'Porsche Macan');
      break;
    case 'Mercedes':
      addModelOption(modelSelect, 'Mercedes-Benz C-Class');
      addModelOption(modelSelect, 'Mercedes-Benz E-Class');
      addModelOption(modelSelect, 'Mercedes-Benz S-Class');
      break;
    default:
      break;
  }
}

function addModelOption(select, modelName) {
  const option = document.createElement('option');
  option.value = modelName;
  option.text = modelName;
  select.add(option);
}