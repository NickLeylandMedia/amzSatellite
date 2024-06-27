import { parse } from "papaparse";

//Parse CSV file with papaparse
async function parseCSV(file: any, header: boolean, skipEmptyLines: boolean) {
  const result = await parse(file, {
    header: header,
    skipEmptyLines: skipEmptyLines,
  });

  return result;
}

export { parseCSV };
