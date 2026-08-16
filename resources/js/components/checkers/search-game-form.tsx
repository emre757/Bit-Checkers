import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { Button } from '@/components/ui/button';
import {
    Field,
    FieldDescription,
    FieldGroup,
    FieldLabel,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';

export function SearchGameForm() {
    const { data, setData, get, errors, processing } = useForm({
        gameId: '',
    });

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        get('/games', {
            preserveState: true,
        });
    }

    return (
        <form onSubmit={submit}>
            <FieldGroup>
                <Field data-invalid={Boolean(errors.gameId)}>
                    <FieldLabel htmlFor="gameId">Game ID</FieldLabel>

                    <Input
                        id="gameId"
                        value={data.gameId}
                        onChange={(event) =>
                            setData('gameId', event.target.value)
                        }
                        aria-invalid={Boolean(errors.gameId)}
                        required
                    />

                    {errors.gameId && (
                        <FieldDescription>{errors.gameId}</FieldDescription>
                    )}
                </Field>

                <Button type="submit" disabled={processing}>
                    Load game
                </Button>
            </FieldGroup>
        </form>
    );
}
