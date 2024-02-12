export default function useGetFieldHelper(field, props, checkNumeric = false) {

  const getField = (option) => {

    const path = field.split('.');

    let value = option;
    for (let segment of path) {
      value = value[segment];
      if (typeof value === "undefined" || value === null) {
        value = null;
        break;
      }
    }

    if (typeof value === "undefined") {
      return null;
    }

    return checkNumeric ? Number(value) : value;
  }

  return {
    getField
  }

}
