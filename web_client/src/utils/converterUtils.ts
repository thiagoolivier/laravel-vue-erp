export const dateConverter = (inputDate: string): string => {
  const date = new Date(inputDate);
  return `${padTo2Digits(date.getDate())}/${padTo2Digits(
    date.getMonth() + 1
  )}/${date.getFullYear()}`;
};

export const dateTimeConverter = (inputDate: string): string => {
  const date = new Date(inputDate);
  return `${padTo2Digits(date.getDate())}/${padTo2Digits(
    date.getMonth() + 1
  )}/${date.getFullYear()} ${date.getHours()}:${padTo2Digits(date.getMinutes())}:${padTo2Digits(date.getSeconds())}`;
};

function padTo2Digits(num: number) {
  return num.toString().padStart(2, '0');
}
