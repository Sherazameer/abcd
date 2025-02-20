import { Crud } from './crud';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';

export class CrudDatabase {
  dataChange: BehaviorSubject<Crud[]> = new BehaviorSubject<Crud[]>([]);
  get data(): Crud[] {
    return this.dataChange.value;
  }
  constructor(aCrud: Crud[]) {
    const crudData = aCrud.slice();
    this.dataChange.next(crudData);
  }
}
